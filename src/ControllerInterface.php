<?php

namespace Shadowlab;

use Shadowlab\Repositories\Configuration;

/**
 * Interface ControllerInterface
 *
 * @package Shadowlab
 */
interface ControllerInterface {
  /**
   * getConfig
   *
   * Returns the config property.
   *
   * @return Configuration
   */
  public function getConfig(): Configuration;
}