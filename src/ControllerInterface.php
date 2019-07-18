<?php

namespace Shadowlab;

use Shadowlab\Repositories\CheatSheet;
use Shadowlab\Repositories\Configuration;
use Shadowlab\Repositories\PostType;

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
   * getSheets
   *
   * Returns the sheets property of our Configuration object
   *
   * @return CheatSheet[]
   */
  public function getSheets (): array;

  /**
   * getPostTypes
   *
   * Returns the sheets property of our Configuration object
   *
   * @return PostType[]
   */
  public function getPostTypes (): array;

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