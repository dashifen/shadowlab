<?php

namespace Shadowlab\Theme\Templates;

use Shadowlab\Repositories\Configuration;
use Shadowlab\Theme\Theme;
use Shadowlab\Repositories\MenuItem;
use Dashifen\WPTemplates\Templates\AbstractTimberTemplate;
use WP_Post;

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
   */
  private function getMenu (): array {

    // unlike a "normal" theme, we aren't using the WordPress navigation menu
    // features here.  instead, our main menu is based on our Cheat Sheets
    // post type.  each menu is comprised of a sheet at the top and then the
    // post types that are linked to those sheets as a submenu.  we'll
    // construct that structure here using our config.yaml file to tell us
    // about the structure of that menu.

    $topLevel = $this->getCheatSheets();
    $config = $this->theme->getController()->getConfig();










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

    return array_map(function(WP_Post $post) {
      return $post->post_title;
    }, $posts);
  }

}