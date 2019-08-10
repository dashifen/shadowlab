<?php

namespace Shadowlab\Framework;

use Shadowlab\Theme\Templates\Homepage;
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

    // WordPress ensures that all roads lead to the theme's index.php.  within
    // that file, we construct this Router and let it identify, construct, and
    // return the template object that we use to display this request.  first,
    // if this is the homepage, we can bug out easily and early.

    if ($route === "/") {
      return new Homepage($this->controller->getTheme());
    }

    // if we're still here then we need to see if we're displaying the post
    // types on a sheet or the entries made within a post type.  our route
    // matches the hierarchy within the \Shadowlab\Theme\Templates namespace,
    // but we need to convert the parts of our route into StudlyCaps as
    // needed.  the following complex array statement does the conversion
    // after splitting our route and filtering out any blanks.

    $objectPathEnding = array_map(function (string $kabobString): string {
      return Controller::toStudlyCaps($kabobString);
    }, array_filter(explode("/", $route)));

    // now, we'll mere the object names from above after the namespace path
    // that isn't represented in our route.  joining that with our namespace
    // separator gives us the fully qualified object name for the template we
    // want to return.  so, we construct it and do so.

    $fullObjectPath = array_merge(
      ["Shadowlab", "Theme", "Templates"],
      $objectPathEnding
    );

    $object = join("\\", $fullObjectPath);
    return new $object($this->controller->getTheme());
  }
}