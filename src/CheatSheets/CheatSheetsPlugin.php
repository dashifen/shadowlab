<?php

namespace Shadowlab\CheatSheets;

use Exception;
use Shadowlab\ShadowlabException;
use Shadowlab\ControllerInterface;
use Shadowlab\Repositories\CheatSheet;
use HaydenPierce\ClassFinder\ClassFinder;
use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\WPHandler\Services\AbstractPluginService;
use Dashifen\WPHandler\Handlers\Plugins\AbstractPluginHandler;

class CheatSheetsPlugin extends AbstractPluginHandler {
  /**
   * @var ControllerInterface
   */
  protected $controller;

  /**
   * CheatSheetsPlugin constructor.
   *
   * @param ControllerInterface $controller
   */
  public function __construct (ControllerInterface $controller) {
    $this->controller = $controller;
    parent::__construct();
  }

  /**
   * getController
   *
   * Returns the controller property.
   *
   * @return ControllerInterface
   */
  public function getController (): ControllerInterface {
    return $this->controller;
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

    $this->addAction("admin_init", "createSheets");
    $this->addAction("init", "initializeServices", 5);
    $this->addAction("init", "registerSheetSlugs", 15);
    $this->addAction("init", "flush", 20);

    $this->addFilter("query_vars", "queryVars");
    $this->addFilter("template_include", "templateInclude");

    // whenever one of our types is saved, not counting the sheets themselves,
    // we want to link the entry we just made to the sheet on which it should
    // appear.  this loop registers a series of save_post_{$postType} hooks,
    // one per type, that does that work for us.

    foreach ($this->controller->getConfig()->postTypes as $postType) {
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
      // iterates at all.  this will probably cause all sorts of havoc
      // elsewhere, though.

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
   * createSheets
   *
   * Creates posts in the database for each of the sheets that we list in
   * our configuration.
   *
   * @return void
   * @throws ShadowlabException
   */
  protected function createSheets (): void {
    foreach ($this->controller->getConfig()->sheets as $sheet) {
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
   * registerSheetSlugs
   *
   * Registers rewrite rules for each sheet so that we can load up pages
   * like /<sheet-name> and have it work.
   *
   * @return void
   */
  protected function registerSheetSlugs (): void {
    foreach ($this->controller->getSheets() as $sheet) {

      // the pattern we build here is one in which the URL begins with our
      // slug, has an optional slash, and nothing else.  this is because our
      // sheet entries (e.g. the statuses on the character sheet) all use the
      // sheet's slug as a part of their URL, too.  thus, we only want these
      // rules to apply to the sheet itself.

      add_rewrite_rule("^{$sheet->slug}/?$", "index.php?sheet_id=" . $sheet->sheetId, "top");
    }
  }

  /**
   * flush
   *
   * Flushes rewrite rules while we're debugging.
   *
   * @return void
   */
  protected function flush (): void {
    if (self::isDebug()) {
      flush_rewrite_rules();
    }
  }

  /**
   * queryVars
   *
   * Filters our query variables.
   *
   * @param array $vars
   *
   * @return array
   */
  protected function queryVars (array $vars): array {
    $vars[] = "sheet_id";
    return $vars;
  }

  /**
   * templateInclude
   *
   * Filters the WordPress template name that we include.  Note:  this has
   * nothing to do with the Twig template that is used by those templates to
   * build actual pages.
   *
   * @param string $template
   *
   * @return string
   */
  protected function templateInclude (string $template): string {
    if (is_numeric(get_query_var("sheet_id"))) {
      $template = locate_template("single-cheat-sheet.php");
    }

    return $template;
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

    $postType = $this->controller->getConfig()->getPostType(get_post_type($postId));
    update_post_meta($postId, "_cheat-sheet-id", $postType->sheetId);
  }
}
