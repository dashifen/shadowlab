<?php

namespace Shadowlab\CheatSheets\Services;

use WP_Admin_Bar;
use Timber\Timber;
use Shadowlab\Repositories\CheatSheet;
use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\Repository\RepositoryException;
use Dashifen\WPHandler\Repositories\MenuItem;
use Dashifen\WPHandler\Repositories\SubmenuItem;
use Dashifen\WPHandler\Handlers\HandlerException;

class MenuModifier extends AbstractShadowlabPluginService {
  /**
   * __call
   *
   * This method overrides the AbstractHandler's __call method so that we can
   * catch the show* methods for our sheets.  all other methods are passed up
   * the extension chain to the original __call method.
   *
   * @param string $method
   * @param array  $arguments
   *
   * @return mixed
   * @throws HandlerException
   */
  public function __call (string $method, array $arguments) {
    if (substr($method, 0, 4) !== "show") {
      return parent::__call($method, $arguments);
    }

    // now, we want to confirm that this show* method matches a sheet.
    // or post type on a sheet.  we do this by attempting to recover the
    // non StudlyCaps version of our information.  then, we see if it's a
    // sheet and, if so, handle it appropriately.

    $sheetOrPostType = $this->undoStudlyCaps($method);
    $config = $this->handler->getController()->getConfig();
    $sheet = $config->getSheet($sheetOrPostType);

    if (!is_null($sheet)) {
      return $this->handleShowSheet($sheet);
    }

    // if we're still here, then maybe it's a post type?  we'll check for that
    // and throw an exception if it's not.  if it is, it is, then we redirect
    // to the edit.php page for that post type.

    $postType = $config->getPostType($sheetOrPostType);

    if (!is_null($postType)) {
      wp_safe_redirect(admin_url("edit.php?post_type=" . $postType->slug));
      wp_die();
    }

    // at the end of all things, if we have some of the show* method that is
    // neither a sheet nor a post type on a sheet, we throw an exception.

    throw new HandlerException("Unknown method: $method",
      HandlerException::INAPPROPRIATE_CALL);
  }

  /**
   * undoStudlyCaps
   *
   * Given a string like showStudlyCaps, returns studly-caps.
   *
   * @param string $studly
   *
   * @return string
   */
  private function undoStudlyCaps (string $studly): string {
    $noShow = substr($studly, 4);
    $dashed = preg_replace("/([a-z])([A-Z])/", '$1-$2', $noShow);
    return strtolower($dashed);
  }

  /**
   * handleShowSheet
   *
   * Builds a page to display sheet entry post types as links to edit.php
   * for each of them.
   *
   * @param CheatSheet $sheet
   *
   * @return string
   */
  private function handleShowSheet (CheatSheet $sheet): string {

    // we have a cheat-sheet-entries.twig template that needs some context
    // about this sheet.  we'll get that context and then use Timber to
    // render it as the string which we return.  note:  Timber also echoes
    // the string, so that's how it ends up on-screen; the return is just
    // to make __call() happy above.

    $context = $this->getSheetTemplateContext($sheet);
    $twig = $this->getPluginDir() . "/assets/cheat-sheet-entries.twig";
    return Timber::render_string(file_get_contents($twig), $context);
  }

  /**
   * getSheetTemplateContext
   *
   * Given a sheet, returns the necessary context data for the template used
   * to display information about it.
   *
   * @param CheatSheet $sheet
   *
   * @return array
   */
  private function getSheetTemplateContext (CheatSheet $sheet): array {
    foreach ($sheet->entries as $postType) {

      // the sheet's entries are listed as their post type.  but, we want
      // both that and their plural display.  so, we can use what we have here
      // to get the rest out of our config object.

      $entries[] = [
        "title" => $this->handler->getController()->getConfig()->getPostType($postType)->plural,
        "type"  => $postType,
      ];
    }

    return [
      "sheet" => [
        "title"   => $sheet->title,
        "entries" => $entries ?? [],
      ]
    ];
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
    if (!$this->isInitialized()) {
      $this->addAction("admin_menu", "addCheatSheetMenus");
      $this->addAction("admin_menu", "removeDefaultMenuItems", 999);
      $this->addAction("admin_bar_menu", "removeDefaultAdminBarItems", 1000);
      $this->addAction("wp_dashboard_setup", "removeDraftWidget", 1000);
      $this->addFilter("add_menu_classes", "updateTopLevelMenuClasses");

      // the adminmenu action happens right after the menu has been displayed
      // in the wp-admin/menu-header.php file.  it's not the same action as the
      // admin_menu one we used above.

      $this->addAction("adminmenu", "alterMenuDisplay");
    }
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
    $config = $this->handler->getController()->getConfig();

    foreach ($config->sheets as $sheet) {

      // for each sheet, we want to add a menu item as a top-level menu.
      // then, we add a sub-menu item within it for each type of entry
      // on that sheet.

      $this->addMenuPage(new MenuItem($this, [
        "pageTitle"  => $sheet->title,
        "capability" => "edit_posts",
        "iconUrl"    => "dashicons-media-spreadsheet",
        "method"     => "show" . $this->getStudlyCaps($sheet->title),
        "position"   => 5,
      ]));

      foreach ($sheet->entries as $postType) {
        $hook = $this->addSubmenuPage(new SubmenuItem($this, [
          "parentSlug" => strtolower(urlencode($sheet->title)),
          "pageTitle"  => $config->getPostType($postType)->plural,
          "method"     => "show" . $this->getStudlyCaps($postType),
          "capability" => "edit_posts",
        ]));

        // rather than using the addAction() method, we're just going to
        // use WP core here so we can use an anonymous function and a closure
        // on the $postType variable.  otherwise, we'd need to jump through
        // some hoops to tell to which editor we wanted to redirect.  this,
        // while not handled the same as our other actions, is easier than
        // jumping through hoops!

        add_action("load-$hook", function () use ($postType) {
          wp_safe_redirect(admin_url("edit.php?post_type=$postType"));
          exit;
        });
      }
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

  /**
   * updateTopLevelMenuClasses
   *
   * Given a menu array, modify its classes as necessary to make our
   * too level menu work like it's supposed to.
   *
   * @param array $menuItems
   *
   * @return array
   */
  protected function updateTopLevelMenuClasses (array $menuItems): array {
    $config = $this->handler->getController()->getConfig();

    // there are two things we hope to do here:  one is add a class to each
    // of our top-level sheet menus that will identify them for us.  the second
    // is to correct the sub-menu display when we're looking at one of our post
    // types which don't trigger the display of the top-level item because of
    // the menu magic we performed above.

    $postType = $config->getPostType($this->getThePostType());
    $submenuSheet = is_null($postType) ? "" : $postType->sheet->title;

    foreach ($menuItems as &$item) {
      if ($config->getSheet($item[0])) {

        // if this item's label is one of our sheets, then we add a static
        // class to this one's classes to identify it.  this is half of the
        // battle here already handled.

        $item[4] .= " menu-cheat-sheet";
        if ($item[0] === $submenuSheet) {

          // now for the other half.  we've determined that this is the sheet
          // parent of the currently displayed post type.  therefore, we want
          // to make sure the menu is shown at this time.

          $item[4] .= " wp-has-submenu wp-has-current-submenu wp-menu-open";
        }
      }
    }

    return $menuItems;
  }

  /**
   * getThePostType
   *
   * Returns the post type we're working with during the current request or
   * null if the request doesn't really focus on posts at this time.
   *
   * @return string
   */
  private function getThePostType (): string {
    if (isset($_GET["post_type"])) {
      return $_GET["post_type"];
    }

    // if we're still here, then we need to see if the page were on has a
    // post and, if so, what type it is.  this is usually the case when we're
    // on the post.php page which, as it turns out, has a post query string
    // parameter.

    if ($_SERVER["PHP_SELF"] === "/wp-admin/post.php") {
      return get_post_type($_GET["post"]);
    }

    return "";
  }

  /**
   * alterMenuDisplay
   *
   * Adds a style and, sometimes, a bit of JavaScript to the DOM to control
   * the display of the menu.
   *
   * @return void
   */
  protected function alterMenuDisplay (): void {

    // the script we might add pertains to highlighting the submenu item
    // for one of our post types when we're messing with it.  we can tell if
    // that's the case by seeing if the query string's post type parameter
    // exists.

    $postType = $this->handler->getController()->getConfig()->getPostType($this->getThePostType());
    if (!is_null($postType)) {

      // boom!  now know we need the script.  we don't enqueue it because
      // then the display happens and there's a slight, but noticeable,
      // delay before our item lights up.  by adding it here, it executes
      // before we fully paint the DOM in the browser.

      $slug = strtolower($postType->plural); ?>

      <script>
        const anchor = document.querySelector("[href$=<?= $slug ?>]");
        anchor.closest("li").classList.add("current");
        anchor.classList.add("current");
      </script>
    <?php } ?>

    <!--suppress CssUnusedSymbol -->
    <style type="text/css">
      /**
       * this we add here because it's easier than a 1-line CSS file that we
       * then have to enqueue.  plus, it keeps all our menu display mods in
       * one place.  since the first submenu item determines the link for the
       * top-level item, we can't remove it, but we can hide it!
       */
      li.menu-cheat-sheet ul.wp-submenu li.wp-first-item {
        display: none;
      }
    </style>

  <?php }
}
