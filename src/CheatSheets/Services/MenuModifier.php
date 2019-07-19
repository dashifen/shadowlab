<?php

namespace Shadowlab\CheatSheets\Services;

use Dashifen\WPHandler\Handlers\HandlerException;
use WP_Admin_Bar;
use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\Repository\RepositoryException;
use Dashifen\WPHandler\Repositories\MenuItem;

class MenuModifier extends AbstractShadowlabPluginService {
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
    $this->addAction("admin_menu", "addCheatSheetMenus");
    $this->addAction("admin_menu", "removeDefaultMenuItems");
    $this->addAction("admin_bar_menu", "removeDefaultAdminBarItems", 1000);
    $this->addAction("wp_dashboard_setup", "removeDraftWidget", 1000);
  }

  /**
   * addCheatSheetsMenu
   *
   * Works with the Configuration object to construct a series of menus
   * which provide access to Cheat Sheets and their entries.
   *
   * @return void
   * @throws RepositoryException
   * @throws HookException
   */
  protected function addCheatSheetMenus (): void {
    foreach ($this->handler->getController()->getSheets() as $sheet) {
      $method = "show" . $this->getStudlyCaps($sheet->title);

      // for each sheet, we want to add a menu item as a top-level menu.
      // then, we add a sub-menu item within it for each type of entry
      // on that sheet.

      $this->addMenuPage(new MenuItem($this->handler, [
        "pageTitle"  => $sheet->title,
        "capability" => "edit_posts",
        "iconUrl"    => "dashicons-media-spreadsheet",
        "method"     => $method,
        "position"   => 5,
      ]));
    }
  }

  /**
   * getStudlyCaps
   *
   * Takes the string we receive and returns it in studly caps.
   *
   * @param string $wimpyString
   *
   * @return string
   */
  private function getStudlyCaps (string $wimpyString): string {

    // splitting our string based on non-word characters give us the set of
    // words that within our wimpy string.  then, we can walk the array and
    // use ucfirst to capitalize each word.  finally, we join it all together
    // and return our studly string.

    return preg_replace("/\W+/", "", $wimpyString);
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
}
