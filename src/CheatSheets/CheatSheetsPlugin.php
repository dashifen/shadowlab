<?php

namespace Shadowlab\CheatSheets;

use Exception;
use Symfony\Component\Yaml\Yaml;
use Shadowlab\ShadowlabException;
use HaydenPierce\ClassFinder\ClassFinder;
use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\Repository\RepositoryException;
use Shadowlab\CheatSheets\Repositories\CheatSheet;
use Symfony\Component\Yaml\Exception\ParseException;
use Shadowlab\CheatSheets\Repositories\Configuration;
use Dashifen\WPHandler\Services\AbstractPluginService;
use Dashifen\WPHandler\Handlers\Plugins\AbstractPluginHandler;
use WP_Admin_Bar;

class CheatSheetsPlugin extends AbstractPluginHandler {

  /**
   * @var Configuration
   */
  protected $config;

  /**
   * CheatSheets constructor.
   *
   * @throws ShadowlabException
   * @throws RepositoryException
   */
  public function __construct () {
    parent::__construct();

    $configFile = ABSPATH . "../config.yaml";

    if (!is_file($configFile)) {
      throw new ShadowlabException("Unable to find config.yaml",
        ShadowlabException::CONFIG_FILE_NOT_FOUND);
    }

    try {

      // YAML is easy to hand type than JSON, but PHP doesn't have a core
      // YAML parser.  so, we'll rely on the one from symfony instead.  then,
      // to help other parts of this plugin "know" what's to be found in our
      // configuration, we'll create a repository out of it and store that.
      // notice that we pass the same set of information to the setters for
      // bot sheets and postTypes; that's intentional.

      $yaml = Yaml::parseFile($configFile);
      $this->config = new Configuration([
        "sheets"    => $yaml["sheets"],
        "postTypes" => $yaml["sheets"],
      ]);
    } catch (ParseException $e) {

      // rather than throw symfony's exception, we'll throw ours.  so, here
      // we convert the former to the latter.

      throw new ShadowlabException("Unable to parse config.yaml",
        ShadowlabException::CONFIG_FILE_INVALID);
    }
  }

  /**
   * findPluginDirectory
   *
   * Returns the name of the directory in which our concrete extension
   * of this class resides.  Note:  this is different from the other method
   * with a similar name, getPluginDir().  this one is protected and for
   * use internal to this object; that one is to extract the protected value
   * of the pluginDir property.
   *
   * @return string
   */
  protected function findPluginDirectory (): string {

    // the base class assumes that this object would be defined inside the
    // plugin folder somewhere.  but because we're absorbing the entire
    // WordPress system into our plugin, that's not the case this time.  so,
    // we'll just specify what we need to specify here.

    return "shadowlab-cheatsheets";
  }


  /**
   * getConfig
   *
   * Returns the config property.
   *
   * @return Configuration
   */
  public function getConfig (): Configuration {
    return $this->config;
  }



  /**
   * initialize
   *
   * Uses addAction() and addFilter() to connect WordPress to the methods
   * of this object's child which are intended to be protected.
   *
   * @return void
   * @throws HookException
   */
  public function initialize (): void {

    // we initialize our services at priority level five here so that they can
    // use the default of ten and know that their priority level in the queue
    // has not yet happened.

    $this->addAction("init", "initializeServices", 5);
    $this->addAction("admin_init", "createSheets");

    // here we remove parts of the default WordPress dashboard UI because
    // we don't need them for this app.  these include posts, pages, media,
    // and comment related stuff.

    $this->addAction("admin_menu", "removeDefaultMenuItems");
    $this->addAction("admin_bar_menu", "removeDefaultAdminBarItems", 1000);
    $this->addAction("wp_dashboard_setup", "removeDraftWidget", 1000);

    // whenever one of our types is saved, not counting the sheets themselves,
    // we want to link the entry we just made to the sheet on which it should
    // appear.  this loop registers a series of save_post_{$postType} hooks,
    // one per type, that does that work for us.

    foreach ($this->config->postTypes as $postType) {
      if ($postType->type !== "cheat-sheet") {
        $this->addAction("save_post_" . $postType->type, "addEntryToSheet");
      }
    }
  }

  /**
   * initializeServices
   *
   * Uses the ClassFinder object to get a list of Services for this object
   * and initializes them.
   *
   * @return void
   */
  protected function initializeServices () {
    try {

      // the ClassFinder object will return an array of all objects found in
      // the specified namespace.  in this case, they're all plugin services.
      // this lets us add services to that folder without having to remember
      // to also add them here for initialization.

      $services = ClassFinder::getClassesInNamespace(
        'Shadowlab\CheatSheets\Services',
        ClassFinder::RECURSIVE_MODE
      );
    } catch (Exception $e) {

      // if there were any problems, we'll just skip the initialization of
      // services by setting an empty array.  then the loop below never
      // iterates at all.

      $services = [];
    }

    foreach ($services as $service) {
      /** @var AbstractPluginService $service */

      if (strpos($service, "Abstract") !== false) {
        continue;
      }

      // services get a reference to this object, their handler.  then, we
      // call their initialize function.  see the objects in the adjacent
      // Services folder for more information on what each of these do.

      $service = new $service($this);
      $service->initialize();
    }
  }

  /**
   * unregisterPostTypes
   *
   * Removes posts and pages from the WordPress ecosystem.
   *
   * @return void
   */
  protected function unregisterPostTypes (): void {
    unregister_post_type("post");
    unregister_post_type("page");
  }

  /**
   * createSheets
   *
   * Creates posts in the database for each of the sheets that we list in
   * our configuration.
   *
   * @return void
   * @throws ShadowlabException
   */
  protected function createSheets (): void {
    foreach ($this->config->sheets as $sheet) {
      if ($sheet->sheetId === 0) {
        $sheetId = $this->createSheet($sheet);
        $sheet->setSheetId($sheetId);
      }

      $this->updateSheet($sheet);
    }
  }

  /**
   * createSheet
   *
   * When we find a sheet that we've not previously created, this method
   * adds it to the database.
   *
   * @param CheatSheet $sheet
   *
   * @return int
   */
  private function createSheet (CheatSheet $sheet): int {
    return wp_insert_post([
      "post_type"   => "cheat-sheet",
      "post_title"  => $sheet->title,
      "post_status" => "publish",
    ]);
  }

  /**
   * updateSheet
   *
   * Updates metadata about our sheets based on their current state in the
   * config file.
   *
   * @param CheatSheet $sheet
   */
  private function updateSheet (CheatSheet $sheet): void {
    update_post_meta($sheet->sheetId, "_entries", $sheet->entries);
  }

  /**
   * removeDefaultMenuItems
   *
   * Removes some of the default menu items from our Dashboard to keep things
   * as clean as possible.
   *
   * @return void
   */
  protected function removeDefaultMenuItems (): void {
    remove_menu_page('edit.php');
    remove_menu_page('edit.php?post_type=page');
    remove_menu_page('upload.php');
    remove_menu_page('edit-comments.php');
  }

  /**
   * removeDefaultAdminBarItems
   *
   * Removes some of the items from our admin bar for core WP features we
   * don't use here.
   *
   * @param WP_Admin_Bar $adminBar
   *
   * @return void
   */
  protected function removeDefaultAdminBarItems (WP_Admin_Bar $adminBar): void {
    $adminBar->remove_menu("new-page");
    $adminBar->remove_node("new-post");
    $adminBar->remove_node("new-media");
    $adminBar->remove_node("comments");
  }

  /**
   * removeDraftWidget
   *
   * Makes sure to hide the quick draft widget on the Dashboard so that no
   * one can cram a post into the database that way.
   */
  protected function removeDraftWidget (): void {
    remove_meta_box("dashboard_quick_press", "dashboard", "side");
  }

  /**
   * addEntryToSheet
   *
   * When an entry is saved, this method adds it to its sheet using the
   * Configuration object to find that sheet.
   *
   * @param int $postId
   *
   * @return void
   */
  protected function addEntryToSheet (int $postId) {

    // this same method is called for all of our registered post types.  so,
    // first we need to get this post's type and then use that to identify the
    // sheet ID to which we add it.

    $postType = $this->config->getPostType(get_post_type($postId));
    update_post_meta($postId, "_cheat-sheet-id", $postType->sheetId);
  }
}
