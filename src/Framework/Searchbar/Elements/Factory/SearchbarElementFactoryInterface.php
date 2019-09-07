<?php

namespace Shadowlab\Framework\Searchbar\Elements\Factory;

use Shadowlab\Framework\Searchbar\Elements\SearchbarResetElement;
use Shadowlab\Framework\Searchbar\Elements\SearchbarSearchElement;
use Shadowlab\Framework\Searchbar\Elements\SearchbarFilterElement;

/**
 * Interface SearchbarElementFactoryInterface
 *
 * @package Shadowlab\Framework\Searchbar
 */
interface SearchbarElementFactoryInterface {
  /**
   * produceElement
   *
   * Uses the parameters to produce a searchbar search element.
   *
   * @param string $name
   * @param string $label
   * @param string $plural
   *
   * @return SearchbarSearchElement
   * @throws SearchbarElementFactoryException
   */
  public function produceSearchElement (string $name, string $label, string $plural = ""): SearchbarSearchElement;

  /**
   * produceFilterElement
   *
   * Uses the parameters to produce a searchbar filter element.
   *
   * @param string $name
   * @param string $label
   * @param array  $options
   * @param string $plural
   *
   * @return SearchbarFilterElement
   * @throws SearchbarElementFactoryException
   */
  public function produceFilterElement (string $name, string $label, array $options, string $plural = ""): SearchbarFilterElement;

  /**
   * produceResetElement
   *
   * Uses the parameters to produce a searchbar reset element.
   *
   * @param string $label
   *
   * @return SearchbarResetElement
   * @throws SearchbarElementFactoryException
   */
  public function produceResetElement (string $label): SearchbarResetElement;
}