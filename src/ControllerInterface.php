<?php

namespace Shadowlab;

use Throwable;
use Shadowlab\Repositories\PostType;
use Shadowlab\Repositories\CheatSheet;
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
   * getAcfFolder
   *
   * Returns the ACF assets folder location.
   *
   * @return string
   * @throws ShadowlabException
   */
  public function getAcfFolder (): string;

  /**
   * setAcfFolder
   *
   * Sets the ACF Folder property.
   *
   * @param string $acfFolder
   *
   * @return void
   * @throws ShadowlabException
   */
  public function setAcfFolder (string $acfFolder): void;

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

  /**
   * isDebug
   *
   * Returns the value for Theme::isDebug().
   *
   * @return bool
   */
  public function isDebug (): bool;

  /**
   * catcher
   *
   * Calls the Theme::catcher() method passing it $e.
   *
   * @param Throwable $e
   *
   * @return void
   */
  public function catcher (Throwable $e): void;
}