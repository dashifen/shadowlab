<?php

namespace Shadowlab\Repositories;

use Dashifen\Repository\Repository;

/**
 * Class CheatSheetEntry
 *
 * @package Shadowlab\Repositories
 * @property-read array  $fields
 * @property-read string $title
 * @property-read string $description
 * @property-read string $book
 * @property-read int    $page
 */
class CheatSheetEntry extends Repository {
  /**
   * @var array
   */
  protected $fields = [];

  /**
   * @var string
   */
  protected $title = "";

  /**
   * @var string
   */
  protected $description = "";

  /**
   * @var string
   */
  protected $book = "";

  /**
   * @var int
   */
  protected $page = 0;

  /**
   * setFields
   *
   * Sets the fields property.
   *
   * @param array $fields
   *
   * @return void
   */
  protected function setFields (array $fields): void {
    $this->fields = $fields;
  }

  /**
   * setTitle
   *
   * Sets the title property.
   *
   * @param string $title
   *
   * @return void
   */
  protected function setTitle (string $title): void {
    $this->title = $title;
  }

  /**
   * setDescription
   *
   * Sets the description property.
   *
   * @param string $description
   *
   * @return void
   */
  protected function setDescription (string $description): void {
    $this->description = $description;
  }

  /**
   * setBook
   *
   * Sets the book property.
   *
   * @param string $book
   *
   * @return void
   */
  protected function setBook (string $book): void {
    $this->book = $book;
  }

  /**
   * setPage
   *
   * Sets the page property.
   *
   * @param int $page
   *
   * @return void
   */
  protected function setPage (int $page): void {
    $this->page = $page;
  }
}