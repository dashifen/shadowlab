<?php

namespace Shadowlab\Framework;

use Dashifen\Session\Session;
use Zend\Diactoros\ServerRequest;

class Request extends \Dashifen\Request\Request {
  /**
   * Request constructor.
   *
   * Specifying implementations of our parameter interfaces so that we can
   * auto-wire our dependency injection container.
   *
   * @param ServerRequest $serverRequest
   * @param Session       $session
   */
  public function __construct (ServerRequest $serverRequest, Session $session) {
    parent::__construct($serverRequest, $session);
  }

}
