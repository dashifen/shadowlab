<?php

namespace Shadowlab\Repositories;

use Dashifen\Repository\Repository;

/**
 * Class ACFDefinition
 *
 * @package Shadowlab\Repositories
 * @property string $title
 * @property string $file
 * @property int    $lastModified
 */
class ACFDefinition extends Repository {
  /**
   * @var string
   */
  protected $title = "";

  /**
   * @var string
   */
  protected $file = "";

  /**
   * @var int
   */
  protected $lastModified = 0;

  /**
   * setTitle
   *
   * Sets the title property.
   *
   * @param string $title
   */
  protected function setTitle (string $title): void {
    $this->title = $title;
  }

  /**
   * setFile
   *
   * Sets the file property.
   *
   * @param string $file
   */
  protected function setFile (string $file): void {
    $this->file = $file;
  }

  /**
   * setLastModified
   *
   * Sets the last modified property.
   *
   * @param int $lastModified
   */
  protected function setLastModified (int $lastModified): void {
    $this->lastModified = $lastModified;
  }
}