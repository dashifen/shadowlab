<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */

namespace Shadowlab;

use League\Container\Container;
use League\Container\ReflectionContainer;
use Zend\Diactoros\ServerRequestFactory;

class ContainerConfig {
  /**
   * @var Container
   */
  private $container;

  /**
   * ContainerConfig constructor.
   *
   * Irony is not injecting dependencies into the object which configures
   * our dependency injection container.  But, to keep the rest of our app
   * as clean as possible, we'll keep all of the information about our
   * Container here.  Even if it means we break the DI rules in this one
   * case.
   */
  public function __construct () {
    $this->container = new Container();
    $this->container->delegate(new ReflectionContainer());
  }

  /**
   * getConfiguredContainer
   *
   * Returns the container property after adding configurations that cannot
   * rely on the auto-wiring from the ReflectionContainer to which we delegated
   * object instantiation in the constructor above.
   *
   * @return Container
   */
  public function getConfiguredContainer(): Container {
    $this->configureRouter();
    $this->configureSession();
    $this->configureRequest();
    return $this->container;
  }

  /**
   * configureRouter
   *
   * Injects a setter whenever we construct a Router object.
   *
   * @return void
   */
  protected function configureRouter (): void {

    // to inject our setter, our container needs to know exactly how to
    // construct our router.  which is a shame; it'd be nicer if we could
    // just add the method call, but that doesn't work.  so, we do all of
    // this.

    $this->container->add(\Shadowlab\Framework\Router::class)
      ->addArgument(\Shadowlab\Framework\Request::class)
      ->addArgument(\Dashifen\Router\Route\Collection\RouteCollection::class)
      ->addArgument(\Dashifen\Router\Route\Factory\RouteFactory::class)
      ->addMethodCall("isAutoRouter", [true]);
  }

  /**
   * configureSession
   *
   * Specifies the session index that we use within this application.
   *
   * @return void
   */
  protected function configureSession (): void {
    $this->container->add(\Dashifen\Session\Session::class)
      ->addArgument("shadowlab-session");
  }

  /**
   * configureRequest
   *
   * Specifies the exact ServerRequest that we use when constructing
   * our Request object.
   *
   * @return void
   */
  protected function configureRequest (): void {

    // if we don't specify that we want to use the ServerRequestFactory to
    // grab our PHP environment then it's left blank.  so, we'll create a
    // factory Callback that we use to make sure we get our environment
    // wherever we need a request.

    $requestFactory = function (): \Shadowlab\Framework\Request {
      $superGlobals = ServerRequestFactory::fromGlobals();
      $session = $this->container->get(\Dashifen\Session\Session::class);
      return new \Shadowlab\Framework\Request($superGlobals, $session);
    };

    $this->container->add(\Shadowlab\Framework\Request::class, $requestFactory);
  }
}