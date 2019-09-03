<?php

namespace Shadowlab\Repositories\SearchBarElements;

use Dashifen\Repository\RepositoryException;

/**
 * Class FilterElement
 *
 * @property-read array $options
 * @package Shadowlab\Repositories\SearchBarElements
 */
class FilterElement extends SearchElement {
  /**
   * @var array
   */
  protected $options = [];

  /**
   * SearchElement constructor.
   *
   * @param string $name
   * @param string $label
   * @param array  $options
   * @param string $plural
   *
   * @throws RepositoryException
   */
  public function __construct (string $name, string $label, array $options, string $plural = "") {

    // since our parent can handle the name, label, and plural variables,
    // we'll send those up to them.  we can set our options using the setter
    // below and we make sure to override the default value of type set in
    // our parent.

    $this->type = "filter";
    $this->setOptions($options);
    parent::__construct($name, $label, $plural);
  }

  /**
   * setOptions
   *
   * Sets the options property.
   *
   * @param array $options
   *
   * @return void
   */
  protected function setOptions (array $options): void {
    $this->options = $options;
  }


}