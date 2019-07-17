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
  public function getConfig (): Configuration;

  /**
   * sanitizeUrlSlug
   *
   * Takes a string and makes sure it doesn't have spaces and is in lower
   * case.  So "Foo Bar" would become "foo-bar," for example.
   *
   * @param string $unsanitary
   *
   * @return string
   */
  public static function sanitizeUrlSlug (string $unsanitary): string;
}