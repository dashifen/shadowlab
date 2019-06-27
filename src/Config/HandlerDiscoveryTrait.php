<?php

namespace Shadowlab\Config;

use Aura\Di\ContainerConfig;

/**
 * Trait ContainerConfigTrait
 *
 * The Router and the Action need to know about how to send people to the
 * right place within our app and what to do when we get there.  thus, the
 * configurators for those two objects use this trait to gain access to
 * these methods.
 *
 * @package Shadowlab\Config
 */
trait HandlerDiscoveryTrait {
  /**
   * getHandlerListPath
   *
   * Returns the path to the adjacent handlerList.php trait.
   *
   * @return string
   */
  protected function getHandlerListPath() {
    $path = pathinfo(__FILE__, PATHINFO_DIRNAME);
    return $path . DIRECTORY_SEPARATOR . "handlerList.php";
  }

  /**
   * getHandlers
   *
   * Given a path to a list of routes, this method executes the file at
   * that path to construct the route array it describes and returns it.
   *
   * @param string $path
   *
   * @return array
   * @throws ContainerConfigException
   */
  protected function getHandlers(string $path): array {
    if (!is_file($path)) {
      throw new ContainerConfigException("Handler list file not found", ContainerConfigException::ROUTE_FILE_NOT_FOUND);
    }

    /** @noinspection PhpIncludeInspection */
    $handlers = require($path);
    return $handlers;
  }
}