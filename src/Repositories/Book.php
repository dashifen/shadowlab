<?php

namespace Shadowlab\Repositories;

use Dashifen\Repository\Repository;
use Dashifen\Repository\RepositoryException;

/**
 * Class Book
 *
 * @package Shadowlab\Repositories
 * @property-read string $title
 * @property-read string $abbr                     
 */
class Book extends Repository {
  /**
   * @var string 
   */
  protected $title = "";

  /**
   * @var string 
   */
  protected $abbr = "";

  /**
   * AbstractRepository constructor.
   *
   * If given an associative data array, loops over its values settings
   * properties that match indices therein.
   *
   * @param array $data
   *
   * @throws RepositoryException
   */
  public function __construct (array $data = []) {

    // $data is the result of the get_field() function which gives us a label
    // and value index within it.  the label is the name of the book and the
    // value is the abbreviation.  we'll call our parent's constructor passing
    // to make these indices more descriptive.

    parent::__construct([
      "title" => $data["label"],
      "abbr" => $data["value"],
    ]);
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
   * setAbbr
   *
   * Sets the abbr (abbreviation) property.
   *
   * @param string $abbr
   *
   * @return void
   */
  protected function setAbbr (string $abbr): void {
    $this->abbr = $abbr;
  }
}