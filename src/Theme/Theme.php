<?php

namespace Shadowlab\Theme;

use Twig_Environment;
use Twig_SimpleFilter;
use Shadowlab\Framework\Controller;
use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\WPHandler\Hooks\Factory\HookFactoryInterface;
use Dashifen\WPHandler\Handlers\Themes\AbstractThemeHandler;

class Theme extends AbstractThemeHandler {
  /**
   * @var Controller
   */
  protected $controller;

  /**
   * Theme constructor.
   *
   * @param HookFactoryInterface $hookFactory
   * @param Controller           $controller
   */
  public function __construct (HookFactoryInterface $hookFactory, Controller $controller) {
    parent::__construct($hookFactory);
    $this->controller = $controller;
  }

  /**
   * getController
   *
   * Returns the controller property
   *
   * @return Controller
   */
  public function getController (): Controller {
    return $this->controller;
  }

  /**
   * initialize
   *
   * Hooks methods of this object into the WordPress ecosystem
   *
   * @return void
   * @throws HookException
   */
  public function initialize (): void {
    if (!$this->isInitialized()) {
      $this->addAction("init", "startSession");
      $this->addFilter("timber/twig", "addTwigFilters");
      $this->addAction("wp_enqueue_scripts", "addAssets");
      $this->addAction("after_setup_theme", "addThemeFeatures");
      $this->addAction("template_redirect", "forceAuthentication");
    }
  }

  /**
   * startSession
   *
   * If the PHP session hasn't already been started, we start it now.
   *
   * @return void
   */
  protected function startSession (): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
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
    $twig->addFilter(new Twig_SimpleFilter("toId", function (string $string): string {
      return preg_replace("/\s+/", "-", strtolower($string));
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
    $this->enqueue("assets/dashifen.css");
    $this->enqueue("assets/dashifen.js");
  }

  /**
   * addThemeFeatures
   *
   * Adds theme features like featured images, menus, sidebars, etc.
   *
   * @return void
   */
  protected function addThemeFeatures (): void {

  }

  /**
   * forceAuthentication
   *
   * If the current session is not authentic, we redirect to the login
   * form and make it so.
   *
   * @return void
   */
  protected function forceAuthentication (): void {
    if (get_current_user_id() === 0) {

      // if we're not logged in, this simply redirects us to the login page
      // and passes the address of the current page over to that one so we can
      // be brought back here after authentication.

      wp_safe_redirect(wp_login_url(get_permalink()));
    }
  }
}