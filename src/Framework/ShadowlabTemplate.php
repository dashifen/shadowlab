<?php

namespace Shadowlab\Framework;

use Shadowlab\Theme\Theme;
use Shadowlab\Repositories\PostType;
use Shadowlab\Repositories\CheatSheet;
use Dashifen\Repository\RepositoryException;
use Shadowlab\Repositories\ShadowlabMenuItem;
use Dashifen\WPTemplates\Templates\AbstractTimberTemplate;

class ShadowlabTemplate extends AbstractTimberTemplate {
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
    $this->theme = $theme;
    parent::__construct($getTimberContext);
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
      "template"  => str_replace("\\", "-", strtolower(get_called_class())),
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
    return sprintf('<%s id="site-title">%s</%s>',
      ($tag = is_home() ? "h1" : "span"), $siteName, $tag);
  }

  /**
   * getMenu
   *
   * Returns an array of MenuItem objects that we can use elsewhere to
   * print the menu on-screen.
   *
   * @return ShadowlabMenuItem[]
   * @throws RepositoryException
   */
  private function getMenu (): array {

    // unlike a "normal" theme, we aren't using the WordPress navigation menu
    // features here.  instead, our main menu is based on our Cheat Sheets
    // post type.  each menu is comprised of a sheet at the top and then the
    // post types that are linked to those sheets as a submenu.  we'll
    // construct that structure here using our Configuration object.

    $sheets = $this->theme->getController()->getSheets();
    foreach ($sheets as $sheet) {
      $submenu = $this->getSubMenu($sheet);

      $menu[] = new ShadowlabMenuItem([
        "label"   => $sheet->title,
        "url"     => home_url($sheet->slug),
        "current" => $this->isCurrentSheet($sheet),
        "classes" => sizeof($submenu) > 0 ? ["item", "with-submenu"] : ["item"],
        "submenu" => $submenu,
      ]);
    }

    return $menu ?? [];
  }

  /**
   * getSubMenu
   *
   * Given a CheatSheet, an array of MenuItems representing the submenu for
   * it's menu item.
   *
   * @param CheatSheet $sheet
   *
   * @return ShadowlabMenuItem[]
   * @throws RepositoryException
   */
  private function getSubMenu (CheatSheet $sheet): array {
    foreach ($sheet->entries as $entry) {
      $postType = $this->theme->getController()->getConfig()->getPostType($entry);
      $submenu[] = new ShadowlabMenuItem([
        "label"   => $postType->plural,
        "url"     => home_url($postType->slug),
        "current" => $this->isCurrentPostType($postType),
        "classes" => ["submenu-item", "item"],
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