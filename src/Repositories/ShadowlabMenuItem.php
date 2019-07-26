<?php

namespace Shadowlab\Repositories;

use Dashifen\Repository\Repository;
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
class ShadowlabMenuItem extends Repository {
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
   * @var ShadowlabMenuItem[]
   */
  protected $submenu = [];

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
   * getClasses
   *
   * Transforms the classes property from an array to a string and returns it.
   *
   * @return string
   */
  public function getClasses (): string {
    return join(" ", $this->classes);
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

  /**
   * setSubmenu
   *
   * Sets the submenu property.
   *
   * @param ShadowlabMenuItem[] $submenu
   *
   * @return void
   * @throws RepositoryException
   */
  protected function setSubmenu(array $submenu): void {
    foreach ($submenu as $item) {
      if (!($item instanceof ShadowlabMenuItem)) {
        throw new RepositoryException("All submenu items must be MenuItems",
          RepositoryException::INVALID_VALUE);
      }
    }

    $this->submenu = $submenu;
  }
}