<?php

namespace Shadowlab\Repositories;

use Dashifen\Repository\Repository;
use Timber\MenuItem as TimberMenuItem;
use Dashifen\Repository\RepositoryException;

/**
 * Class MenuItem
 *
 * @package Shadowlab\Theme\Repositories
 * @property $classes
 * @property $current
 * @property $url
 * @property $label
 */
class MenuItem extends Repository {
  /**
   * @var array
   */
  protected $classes = [];

  /**
   * @var bool
   */
  protected $current = false;

  /**
   * @var string
   */
  protected $url = "";

  /**
   * @var string
   */
  protected $label = "";

  /**
   * MenuItem constructor.
   *
   * Uses properties of the TimberMenuItem property to initialize our
   * container.
   *
   * @param TimberMenuItem $item
   *
   * @throws RepositoryException
   */
  public function __construct (TimberMenuItem $item = null) {
    if (!is_null($item)) {
      $data = [
        "label"   => $item->get_field("name"),
        "classes" => array_filter($item->classes),
        "current" => $item->current || $item->current_item_ancestor || $item->current_item_parent,
        "url"     => $item->url,
      ];
    }

    parent::__construct($data ?? []);
  }

  /**
   * setClasses
   *
   * Sets the classes property by merging the current value with the
   * array of classes passed to this method.
   *
   * @param array $classes
   *
   * @return void
   */
  protected function setClasses (array $classes): void {
    $this->classes = array_merge($this->classes, $classes);
  }

  /**
   * setCurrent
   *
   * Sets the current property and adds a class based on the state of
   * that flag.
   *
   * @param bool $current
   *
   * @return void
   */
  protected function setCurrent (bool $current): void {
    $this->current = $current;
    $currentClass = $current ? "is-current" : "is-not-current";
    $this->setClasses([$currentClass]);
  }

  /**
   * setUrl
   *
   * Sets the URL property.
   *
   * @param string $url
   *
   * @return void
   * @throws RepositoryException
   */
  protected function setUrl (string $url): void {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
      throw new RepositoryException("Invalid URL: $url",
        RepositoryException::INVALID_VALUE);
    }

    $this->url = $url;
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
}