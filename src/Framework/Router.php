<?php

namespace Shadowlab\Framework;

use Shadowlab\Framework\Theme\AbstractShadowlabTemplate;

/**
 * Class Router
 *
 * @package Shadowlab\Framework
 */
class Router {
  /**
   * @var Controller
   */
  protected $controller;

  /**
   * Router constructor.
   *
   * @param Controller $controller
   */
  public function __construct (Controller $controller) {
    $this->controller = $controller;
  }

  /**
   * getTemplate
   *
   * Uses the current route to return the appropriate template object.
   *
   * @return AbstractShadowlabTemplate
   */
  public function getTemplate (): AbstractShadowlabTemplate {
    $route = $_SERVER["PHP_SELF"];

    if ($route === "/") {

      // if we're on the homepage, the algorithm we use for everything else
      // doesn't work.  so, for it, we'll just specify the homepage template
      // directly as follows.

      $object = '\Shadowlab\Theme\Templates\Homepage';
    } else {

      // otherwise, our routes match the folder structure that we've created
      // for the Shadowlab\Theme\Templates namespace.  so, we can construct
      // template objects sort of like a PSR-4 include.  we split up our
      // route into its parts, filter out any blank ones, then pass it all
      // through array_map() where we convert from the kabob-case that URLs
      // prefer into StudlyCaps for our object names.

      $objectPathEnding = array_map(function (string $kabobString): string {
        return Controller::toStudlyCaps($kabobString);
      }, array_filter(explode("/", $route)));

      // now, we'll merge the object names from above after the namespace path
      // that isn't represented in our route.  joining that with our namespace
      // separator gives us the fully qualified object name for the template we
      // want to return.

      $fullObjectPath = array_merge(["Shadowlab", "Theme", "Templates"], $objectPathEnding);
      $object = join("\\", $fullObjectPath);
    }

    // $object is now the fully namespaced class name for the template that
    // should be used for this route.  we can construct it and use the DI
    // container within our controller to pass it the arguments that it needs.

    return new $object(
      $this->controller->getTheme(),
      $this->controller->getSearchbar()
    );
  }
}