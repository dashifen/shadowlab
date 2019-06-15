<?php

namespace Shadowlab\Config\ContainerConfig;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

class Exceptionator extends ContainerConfig {
  /**
   * define
   *
   * Defines the means by which the Exceptionator is instantiated upon
   * request.
   *
   * @param Container $di
   */
  public function define(Container $di): void {

    // our exception and error handling object requires information
    // related to what request a person has made in order to function.
    // we can inject the request service that we create in ./Request.php.

    $di->params['Dashifen\Exceptionator\Exceptionator']['request'] = $di->lazyGet('request');
  }
}
