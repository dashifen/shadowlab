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
 * @property-read Book   $book
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
  protected $url;

  /**
   * @var Book
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
   * setUrl
   *
   * Sets the URL property; we don't use filter_var() to confirm it because
   * it's likely to be a WP permalink and not a full address.
   *
   * @param string $url
   *
   * @return void
   */
  protected function setUrl (string $url): void {
    $this->url = $url;
  }

  /**
   * setBook
   *
   * Sets the book property.
   *
   * @param Book $book
   *
   * @return void
   */
  protected function setBook (Book $book): void {
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