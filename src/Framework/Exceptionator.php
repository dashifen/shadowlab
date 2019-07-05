<?php

namespace Shadowlab\Framework;

use Dashifen\Request\RequestInterface;

class Exceptionator extends \Dashifen\Exceptionator\Exceptionator {
  /**
   * Exceptionator constructor.
   *
   * Specifying the object that satisfies our RequestInterface so that
   * we can auto-wire our dependency injection container.
   *
   * @param Request|null $request
   */
  public function __construct (?Request $request = null) {
    parent::__construct($request);
  }

}