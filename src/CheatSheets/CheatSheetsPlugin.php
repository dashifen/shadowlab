<?php

namespace Shadowlab\CheatSheets;

use Shadowlab\Framework\Exception;
use Shadowlab\Framework\Controller;
use Shadowlab\Traits\ConfigurationTrait;
use Dashifen\WPHandler\Handlers\HandlerException;
use Shadowlab\Repositories\CheatSheets\CheatSheet;
use Dashifen\WPHandler\Hooks\Factory\HookFactoryInterface;
use Dashifen\WPHandler\Handlers\Plugins\AbstractPluginHandler;
use Dashifen\WPHandler\Hooks\Collection\Factory\HookCollectionFactoryInterface;
use Dashifen\WPHandler\Agents\Collection\Factory\AgentCollectionFactoryInterface;

class CheatSheetsPlugin extends AbstractPluginHandler {
  use ConfigurationTrait;

  /**
   * @var Controller
   */
  protected $controller;

  /**
   * CheatSheetsPlugin constructor.
   *
   * @param HookFactoryInterface            $hookFactory
   * @param HookCollectionFactoryInterface  $hookCollectionFactory
   * @param AgentCollectionFactoryInterface $agentCollectionFactory
   * @param Controller                      $controller
   */
  public function __construct (
    HookFactoryInterface $hookFactory,
    HookCollectionFactoryInterface $hookCollectionFactory,
    AgentCollectionFactoryInterface $agentCollectionFactory,
    Controller $controller
  ) {
    parent::__construct($hookFactory, $hookCollectionFactory, $agentCollectionFactory);
    $this->controller = $controller;
  }

  /**
   * getController
   *
   * Returns the controller property.
   *
   * @return Controller
   */
  public function getController (): Controller {
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
   * @throws HandlerException
   */
  public function initialize (): void {
    if (!$this->isInitialized()) {
      $this->addAction("init", "forceSSL", 1);

      // the initializeAgents method is defined by our parent.  doesn't mean
      // we can't use it here as a callback though!  we run it at 5 so that the
      // Agents it initializes can use the default priority of 10 knowing that
      // it hasn't happened yet.  then we flush since those Agents might have
      // messed with the permalinks (hint:  they definitely do).

      $this->addAction("init", "initializeAgents", 5);
      $this->addAction("init", "flush", 20);

      $this->addAction("admin_init", "createSheets");

      // whenever one of our types is saved, not counting the sheets themselves,
      // we want to link the entry we just made to the sheet on which it should
      // appear.  this loop registers a series of save_post_{$postType} hooks,
      // one per type, that does that work for us.

      foreach ($this->getPostTypes() as $postType) {
        if ($postType->type !== "cheat-sheet") {
          $this->addAction("save_post_" . $postType->type, "addEntryToSheet");
        }
      }
    }
  }

  /**
   * createSheets
   *
   * Creates posts in the database for each of the sheets that we list in
   * our configuration.
   *
   * @return void
   * @throws Exception
   */
  protected function createSheets (): void {
    foreach ($this->getCheatSheets() as $sheet) {
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
   * forceSSL
   *
   * If we're not using SSL, here we start doing so.
   *
   * @return void
   */
  protected function forceSSL (): void {
    if ($_SERVER["HTTPS"] !== "on") {
      $secure = "https://" . $_SERVER["SERVER_NAME"] . "/" . $_SERVER["REQUEST_URI"];
      wp_safe_redirect($secure);
      die();
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

    $postType = $this->getPostType(get_post_type($postId));
    update_post_meta($postId, "_cheat-sheet-id", $postType->sheet->sheetId);
  }
}
