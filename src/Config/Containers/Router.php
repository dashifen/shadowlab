<?php

namespace Shadowlab\Config\Containers;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Dashifen\Router\RouterException;
use Shadowlab\Config\HandlerDiscoveryTrait;
use Shadowlab\Config\ContainerConfigException;
use Dashifen\Router\Route\Collection\RouteCollection;

class Router extends ContainerConfig {
  use HandlerDiscoveryTrait;

  /**
   * define
   *
   * Defines the means by which our Router is both filled with routes to
   * route and then constructed as we need it.
   *
   * @param Container $container
   *
   * @throws RouterException
   */
  public function define (Container $container): void {

    // our Router object takes over from WordPress after WP leads us to
    // this site's theme's index.php.  thus, we need a way to match the
    // handlers that we expect to find in the Shadowlab.  because we're in
    // WordPress we can use the power of filters here to start with the
    // default pattern and allow other parts of this app to alter the
    // route-matching pattern herein.  then, that $pattern becomes an
    // argument for our RouteCollection object.

    $pattern = RouteCollection::ACTION_PARAMETER_PATTERN;
    $pattern = apply_filters("shadowlab_wildcard_pattern", $pattern, $container);
    $container->params['Dashifen\Router\Route\Collection\RouteCollection']['wildcardPattern'] = $pattern;

    // we also need to define the parameters for our Router as follows;
    // notice that the RouteCollection becomes a part of this, so the
    // $pattern we constructed above therefore becomes a part of the
    // Router, too.

    $container->params['Dashifen\Router\Router']['request'] = $container->lazyGet('request');
    $container->params['Dashifen\Router\Router']['collection'] = $container->lazyNew('Dashifen\Router\Route\Collection\RouteCollection');
    $container->params['Dashifen\Router\Router']['factory'] = $container->lazyNew('Dashifen\Router\Route\Factory\RouteFactory');

    // the last parameter for our Router is the array of routes.  just
    // like our pattern above, we want a way to try and define the primary
    // routes here, but let other parts of this system add to it.

    try {
      $handlerListPath = $this->getHandlerListPath();
      $handlers = $this->getHandlers($handlerListPath);

      // like the pattern above, we want to let other parts of this app add
      // their own handlers.  so, we'll filter what we got here as our default
      // and they can hook it to do their work.

      $handlers = apply_filters("shadowlab_handlers", $handlers, $container);
      $routes = $this->extractRoutes($handlers);
    } catch (ContainerConfigException $exception) {
      throw new RouterException($exception->getMessage(), RouterException::UNKNOWN_ERROR, $exception);
    }

    $container->params['Dashifen\Router\Router']['handlers'] = $handlers;
  }

  /**
   * extractRoutes
   *
   * Takes the list of $routes and transforms them for our Router.
   *
   * @param array $handlers
   *
   * @return array
   */
  protected function extractRoutes (array $handlers): array {

    // the list of $routes as it stands now has a lot of information in it
    // that's for our Action configuration.  the Router doesn't need any of
    // that so we'll transform the raw data we have here to distill it into
    // only what our Router needs here.

    $routes = [];
    foreach ($handlers as $handler) {

      // the $handler object has a routes property.  that's an array with
      // GET or POST indices that define the routes within our app.  some
      // routes are prefixed with an exclamation point; these are public
      // and the rest are private.

      foreach ($handler->routes as $method => $route) {
        $public = preg_match("/(?<=!)(.+)/", $route, $matches);

        $routes[] = [
          "method"  => $method,
          "path"    => $public ? $matches[0] : $route,
          "action"  => $handler->action,
          "private" => !$public,
        ];
      }
    }

    return $routes;
  }
}