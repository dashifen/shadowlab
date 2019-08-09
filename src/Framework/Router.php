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

  }

}