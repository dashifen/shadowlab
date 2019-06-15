<?php

namespace Shadowlab\Config\Containers;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Aura\Di\Exception\ContainerLocked;
use Aura\Di\Exception\ServiceNotObject;

class Database extends ContainerConfig {
  /**
   * define
   *
   * Defines the means by which our database object is instantiated upon
   * request for such an object.
   *
   * @param Container $container
   *
   * @throws ContainerLocked
   * @throws ServiceNotObject
   */
  public function define(Container $container): void {
    $container->set("database", $container->lazyNew('Shadowlab\Framework\Database\Database'));
  }
}
