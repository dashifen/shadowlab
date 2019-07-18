<?php

namespace Shadowlab\CheatSheets\Services;

use Dashifen\Repository\RepositoryException;
use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\WPHandler\Repositories\MenuItem;
use WP_Admin_Bar;

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
    $startingLocation = 4;
    foreach ($this->handler->getController()->getSheets() as $sheet) {

      // for each sheet, we want to add a menu item as a top-level menu.
      // then, we add a sub-menu item within it for each type of entry
      // on that sheet.

      $menuItem = new MenuItem($this->handler, [
        "page_title" => $sheet->title,
        "capability" => "edit_post",
        "position" => ++$startingLocation,
      ]);


      $this->addMenuPage($menuItem);
    }
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
