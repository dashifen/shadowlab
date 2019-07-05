<?php

namespace Shadowlab\Framework;

use Dashifen\Router\RouterException;
use Dashifen\Repository\RepositoryException;
use Dashifen\Router\Route\Factory\RouteFactory;
use Dashifen\Router\Route\Collection\RouteCollection;
use Dashifen\Router\Route\Factory\RouterFactoryException;
use Dashifen\Router\Route\Collection\RouteCollectionException;

class Router extends \Dashifen\Router\Router {
  /**
   * Router constructor.
   *
   * Specifying the implementation of our interfaces that are used within
   * this application to allow for auto-wiring within our dependency injection
   * container.
   *
   * @param Request         $request
   * @param RouteCollection $collection
   * @param RouteFactory    $factory
   *
   * @throws RepositoryException
   * @throws RouteCollectionException
   * @throws RouterFactoryException
   * @throws RouterException
   */
  public function __construct (
    Request $request,
    RouteCollection $collection,
    RouteFactory $factory
  ) {
    parent::__construct($request, $collection, $factory);
  }

  /**
   * transformAction
   *
   * Given an array of actions parts (e.g. [foo, bar, baz]), returns
   * the name of our action in StudlyCaps (e.g. FooBarBaz).
   *
   * @param array $actionParts
   *
   * @return string
   */
  protected function transformAction (array $actionParts): string {

    // the actions within this app all end with the word Action to
    // differentiate them from the Domain and other part of each route's
    // handler.  also, since this whole app operates off of get method,
    // we can remove that from the names as well.

    return substr(parent::transformAction($actionParts), 3) . "Action";
  }
}