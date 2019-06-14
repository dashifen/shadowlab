<?php

namespace Shadowlab\Theme;

use Twig_Environment;
use Twig_SimpleFilter;
use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\WPHandler\Handlers\AbstractHandler;

class Theme extends AbstractHandler {
  /**
   * initialize
   *
   * Hooks methods of this object into the WordPress ecosystem
   *
   * @return void
   * @throws HookException
   */
  public function initialize (): void {
    $this->addFilter("timber/twig", "addTwigFilters");
    $this->addAction("wp_enqueue_scripts", "addAssets");
    $this->addAction("after_setup_theme", "addThemeFeatures");
  }


  /**
   * addTwigFilters
   *
   * Adds twig filters to our Twig Environment for use in our templates.
   *
   * @param Twig_Environment $twig
   *
   * @return Twig_Environment
   */
  protected function addTwigFilters (Twig_Environment $twig): Twig_Environment {
    $twig->addFilter(new Twig_SimpleFilter("esc_attr", function ($string) {
      return esc_attr($string);
    }));

    $twig->addFilter(new Twig_SimpleFilter("esc_url", function ($string) {
      return esc_url($string);
    }));

    return $twig;
  }

  /**
   * addAssets
   *
   * Enqueues the CSS and JS files that we use within this theme so
   * that they are added to the DOM.
   *
   * @return void
   */
  protected function addAssets (): void {
    $this->enqueue("//fonts.googleapis.com/css?family=Iceland:400,700|Droid+Sans:400,700|Droid+Serif:400italic,700italic");
//    $this->enqueue( "assets/dashifen.css" );
//    $this->enqueue( "assets/dashifen.js" );
  }

  /**
   * addThemeFeatures
   *
   * Adds theme features like featured images, menus, sidebars, etc.
   *
   * @return void
   */
  protected function addThemeFeatures () {
    register_nav_menus([
      "main"   => "Main Menu",
      "footer" => "Footer",
    ]);

    // for twitter and facebook sharing, we need some extra image sizes.
    // of course, they can't agree on one size for both platforms so that
    // means we need two.  the true flags mean we crop to these exact
    // dimensions.

    add_image_size("twImage", 1200, 675, true);
    add_image_size("fbImage", 1200, 630, true);
  }
}