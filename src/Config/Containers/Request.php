<?php

namespace Shadowlab\Config\Containers;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Aura\Di\Exception\ContainerLocked;
use Aura\Di\Exception\ServiceNotObject;
use Zend\Diactoros\ServerRequestFactory;

class Request extends ContainerConfig {
  /**
   * define
   *
   * Defines the means by which session and request objects are instantiated
   * upon request for such objects.
   *
   * @param Container $container
   *
   * @throws ContainerLocked
   * @throws ServiceNotObject
   */
  public function define(Container $container): void {

    // while the request and session objects are different, they're both a
    // part of the request as it exists on the server.  plus, the former
    // requires one of the latter, so we'll tell our app how to make the both
    // of them here.

    $container->params['Dashifen\Session\Session']["index"] = 'Shadowlab\Session';
    $container->params['Dashifen\Request\Request']["request"] = ServerRequestFactory::fromGlobals();
    $container->params['Dashifen\Request\Request']["session"] = $container->lazyGet("session");

    // both our session and our request become services.  that's because we
    // always want to reference the same one.  both would work as "regular"
    // objects, but this avoids re-instantiating things and, in the case of
    // the request, calling the above static method over and over again.

    $container->set("session", $container->lazyNew('\Dashifen\Session\Session'));
    $container->set("request", $container->lazyNew('\Dashifen\Request\Request'));
  }
}