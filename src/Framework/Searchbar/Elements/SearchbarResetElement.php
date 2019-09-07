<?php

namespace Shadowlab\Framework\Searchbar\Elements;

use Dashifen\Repository\Repository;
use Dashifen\Repository\RepositoryException;

class SearchbarResetElement extends Repository implements SearchbarElementInterface {
  /**
   * @var string
   */
  protected $type = "reset";

  /**
   * @var string
   */
  protected $label = "";

  /**
   * ResetElement constructor.
   *
   * @param string $label
   *
   * @throws RepositoryException
   */
  public function __construct (string $label) {
    parent::__construct(["label" => $label]);
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