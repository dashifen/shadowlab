<?php

namespace Shadowlab\Repositories\SearchBarElements;

use ICanBoogie\Inflector;
use Dashifen\Repository\Repository;
use Dashifen\Repository\RepositoryException;

/**
 * Class Search
 *
 * @property-read string $type
 * @property-read string $name
 * @property-read string $label
 * @property-read string $plural
 * @package Shadowlab\Repositories\SearchBarElements
 */
class SearchElement extends Repository {
  /**
   * @var string
   */
  protected $type = "search";

  /**
   * @var string
   */
  protected $name = "";

  /**
   * @var string
   */
  protected $label = "";

  /**
   * @var string
   */
  protected $plural = "";

  /**
   * SearchElement constructor.
   *
   * @param string $name
   * @param string $label
   * @param string $plural
   *
   * @throws RepositoryException
   */
  public function __construct (string $name, string $label, string $plural = "") {
    $plural = empty($plural)
      ? $this->pluralize($label)
      : $plural;

    parent::__construct([
      "name"   => $name,
      "label"  => $label,
      "plural" => $plural,
    ]);
  }

  /**
   * pluralize
   *
   * Pluralizes the parameter using the icanboogie/inflector object.
   *
   * @param string $singular
   *
   * @return string
   */
  protected function pluralize(string $singular): string {

    // even though this is a one-liner, we moved it to a method so that our
    // children could inherit it.

    return Inflector::get("en")->pluralize($singular);
  }

  /**
   * setName
   *
   * Sets the name property.
   *
   * @param string $name
   *
   * @return void
   */
  protected function setName (string $name): void {
    $this->name = $name;
  }

  /**
   * setLabel
   *
   * Sets the label property.
   *
   * @param string $label
   *
   * @return void
   */
  protected function setLabel (string $label): void {
    $this->label = $label;
  }

  /**
   * setPlural
   *
   * Sets the plural property.
   *
   * @param string $plural
   *
   * @return void
   */
  protected function setPlural (string $plural): void {
    $this->plural = $plural;
  }
}