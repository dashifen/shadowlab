<?php

namespace Shadowlab\Theme\Templates\Character;

use Shadowlab\Framework\Templates\AbstractCheatSheetTemplate;
use Shadowlab\Framework\Searchbar\Elements\Factory\SearchbarElementFactoryException;

class EdgeActionsTemplate extends AbstractCheatSheetTemplate {
  /**
   * transformContent
   *
   * Transforms the content of a post in case there's more to do to it for
   * our sheet than what WordPress can do via the the_content filter.
   *
   * @param string $content
   *
   * @return string
   */
  protected function transformContent (string $content): string {
    return $content;
  }

  /**
   * transformFieldLabel
   *
   * Transforms the specified label based on the needs of a specific cheat
   * sheet.
   *
   * @param string $label
   *
   * @return string
   */
  protected function transformFieldLabel (string $label): string {
    return $label;
  }

  /**
   * transformFieldValue
   *
   * Transforms an ACF field value based on the needs of a specific cheat
   * sheet.  Since such values are of mixed type, we won't really know what
   * types are transformed and returned here.
   *
   * @param mixed  $value
   * @param string $label
   *
   * @return mixed
   */
  protected function transformFieldValue ($value, string $label) {
    return $value;
  }

  /**
   * getSearchbar
   *
   * Uses the headers and entries for this sheet as well as the searchbar
   * property to produce the HTML necessary for the searchbar on this sheet.
   *
   * @param array $headers
   * @param array $entries
   *
   * @return array
   * @throws SearchbarElementFactoryException
   */
  protected function getSearchbar (array $headers, array $entries): array {

    // the sheet for statuses only needs the entry title and book filters.
    // this means that in this case we do not actually need our headers, only
    // the book data in our entries.

    return [
      $this->searchbarElementFactory->produceSearchElement("title", "Edge Action"),
      $this->searchbarElementFactory->produceFilterElement("action_type", "Type", ["boost" => "Boost", "action" => "Action"]),
      $this->searchbarElementFactory->produceFilterElement("edge_cost", "Cost", ["1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5"]),
      $this->searchbarElementFactory->produceFilterElement("book", "Book", $this->extractBooksFromEntries($entries)),
      $this->searchbarElementFactory->produceResetElement("Reset"),
    ];
  }
}
