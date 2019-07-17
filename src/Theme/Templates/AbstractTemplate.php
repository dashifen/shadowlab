<?php

namespace Shadowlab\Theme\Templates;

use WP_Post;
use Shadowlab\Controller;
use Shadowlab\Theme\Theme;
use Shadowlab\Repositories\PostType;
use Shadowlab\Repositories\MenuItem;
use Shadowlab\Repositories\CheatSheet;
use Shadowlab\Repositories\Configuration;
use Dashifen\Repository\RepositoryException;
use Dashifen\WPTemplates\Templates\AbstractTimberTemplate;

class AbstractTemplate extends AbstractTimberTemplate {
  /**
   * @var Theme
   */
  protected $theme;

  /**
   * AbstractTemplate constructor.
   *
   * @param Theme $theme
   * @param bool  $getTimberContext
   */
  public function __construct (Theme $theme, bool $getTimberContext = false) {
    parent::__construct($getTimberContext);
    $this->theme = $theme;
  }

  /**
   * setContext
   *
   * Sets the context property filling it with data pertinent to the
   * template being displays.  Typically, all the work to do so exists
   * within this method, but data can be passed to it which should be
   * merged with the work performed herein.
   *
   * @param array $context
   *
   * @return void
   * @throws RepositoryException
   */
  public function setContext (array $context = []): void {
    $this->context = [
      "year"      => date("Y"),
      "siteUrl"   => home_url(),
      "template"  => str_replace("/", "-", strtolower(get_called_class())),
      "siteName"  => ($siteName = get_bloginfo("name")),
      "siteTitle" => $this->getSiteTitle($siteName),
      "menu"      => $this->getMenu(),
    ];
  }

  /**
   * getSiteTitle
   *
   * Returns the site logo and header element.
   *
   * @param string $siteName
   *
   * @return string
   */
  private function getSiteTitle (?string $siteName): string {
    return $this->getLogo() . $this->getHeader($siteName);
  }

  /**
   * getLogo
   *
   * Returns an <img> tag for our logo.
   *
   * @return string
   */
  private function getLogo (): string {
    $imagePath = $this->theme->getStylesheetUrl() . "/assets/images/shadowrun-logo-totem.png";
    return '<img src="' . $imagePath . '" alt="the shadowrun logo" width="150">';
  }

  /**
   * getHeader
   *
   * Returns the header element as either an <h1> or a <span> based on
   * whether or not we're on the homepage.
   *
   * @param string $siteName
   *
   * @return string
   */
  private function getHeader (string $siteName): string {
    $tag = is_home() ? "h1" : "span";
    return sprintf('<%s id="site-title">%s</%s>', $tag, $siteName, $tag);
  }

  /**
   * getMenu
   *
   * Returns an array of MenuItem objects that we can use elsewhere to
   * print the menu on-screen.
   *
   * @return MenuItem[]
   * @throws RepositoryException
   */
  private function getMenu (): array {

    // unlike a "normal" theme, we aren't using the WordPress navigation menu
    // features here.  instead, our main menu is based on our Cheat Sheets
    // post type.  each menu is comprised of a sheet at the top and then the
    // post types that are linked to those sheets as a submenu.  we'll
    // construct that structure here using our Configuration object.

    $sheets = $this->getCheatSheets();
    $config = $this->theme->getController()->getConfig();
    foreach ($sheets as $sheetTitle) {
      $sheet = $config->getSheet($sheetTitle);
      $submenu = $this->getSubMenu($sheet, $config);

      $menu[] = new MenuItem([
        "label"   => $sheetTitle,
        "url"     => home_url($sheet->slug),
        "classes" => sizeof($submenu) > 0 ? "item with-submenu" : "item",
        "current" => $this->isCurrentSheet($sheet),
      ]);
    }

    return $menu ?? [];
  }

  /**
   * getCheatSheets
   *
   * Returns an array of registered cheat sheet titles.
   *
   * @return array
   */
  private function getCheatSheets (): array {
    $posts = get_posts(["post_type" => "cheat-sheet"]);

    // all our calling scope wants is the titles, so we'll use array_map()
    // to make a new array containing only those.

    return array_map(function (WP_Post $post) {
      return $post->post_title;
    }, $posts);
  }

  /**
   * getSubMenu
   *
   * Given a CheatSheet, an array of MenuItems representing the submenu for
   * it's menu item.
   *
   * @param CheatSheet    $sheet
   * @param Configuration $config
   *
   * @return MenuItem[]
   * @throws RepositoryException
   */
  private function getSubMenu (CheatSheet $sheet, Configuration $config): array {
    foreach ($sheet->entries as $entry) {
      $postType = $config->getPostType($entry);

      $submenu[] = new MenuItem([
        "label"   => $postType->plural,
        "url"     => home_url($postType->slug),
        "current" => $this->isCurrentPostType($postType),
        "classes" => "submenu-item item",
      ]);
    }

    return $submenu ?? [];
  }

  /**
   * isCurrentPostType
   *
   * Returns true if this post type is the one we're currently displaying.
   *
   * @param PostType $postType
   *
   * @return bool
   */
  private function isCurrentPostType (PostType $postType): bool {

    // using the global $wp object, we can get at the current WordPress
    // request.  then, if our post type's slug is found within it, this one
    // is current.

    return strpos($GLOBALS["wp"]->request, $postType->slug) !== false;
  }

  /**
   * isCurrentSheet
   *
   * Returns true if this sheet or one of it's entries is currently being
   * shown.
   *
   * @param CheatSheet $sheet
   *
   * @return bool
   */
  private function isCurrentSheet (CheatSheet $sheet): bool {
    return strpos($GLOBALS["wp"]->request, $sheet->slug) !== false;
  }
}