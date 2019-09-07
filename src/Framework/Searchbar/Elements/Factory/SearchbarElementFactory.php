<?php

namespace Shadowlab\Framework\Searchbar\Elements\Factory;

use Dashifen\Repository\RepositoryException;
use Shadowlab\Framework\Searchbar\Elements\SearchbarResetElement;
use Shadowlab\Framework\Searchbar\Elements\SearchbarSearchElement;
use Shadowlab\Framework\Searchbar\Elements\SearchbarFilterElement;
use Shadowlab\Framework\Searchbar\Elements\SearchbarElementInterface;

class SearchbarElementFactory implements SearchbarElementFactoryInterface {
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
  public function produceSearchElement (string $name, string $label, string $plural = ""): SearchbarSearchElement {
    $element = null;

    try {
      $element = new SearchbarSearchElement($name, $label, $plural);
    } catch (RepositoryException $exception) {
      throw new SearchbarElementFactoryException("Unable to construct $type element",
        SearchbarElementFactoryException::COULD_NOT_CONSTRUCT);
    }

    return $element;
  }

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
  public function produceFilterElement (string $name, string $label, array $options, string $plural = ""): SearchbarFilterElement {
    $element = null;

    try {
      $element = new SearchbarFilterElement($name, $label, $options, $plural);
    } catch (RepositoryException $exception) {
      throw new SearchbarElementFactoryException("Unable to construct $type element",
        SearchbarElementFactoryException::COULD_NOT_CONSTRUCT);
    }

    return $element;
  }

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
  public function produceResetElement (string $label): SearchbarResetElement {
    $element = null;

    try {
      $element = new SearchbarResetElement($label);
    } catch (RepositoryException $exception) {
      throw new SearchbarElementFactoryException("Unable to construct $type element",
        SearchbarElementFactoryException::COULD_NOT_CONSTRUCT);
    }

    return $element;
  }
}