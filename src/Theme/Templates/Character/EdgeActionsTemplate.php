<?php

namespace Shadowlab\Theme\Templates\Character;

use Shadowlab\Framework\Templates\AbstractCheatSheetTemplate;
use Shadowlab\Framework\Searchbar\Elements\Factory\SearchbarElementFactoryException;

class EdgeActionsTemplate extends AbstractCheatSheetTemplate {
  /**
   * transformTitle
   *
   * Transforms the title for a CheatSheetEntry based on the post's title
   * and the fields for it.
   *
   * @param string $title
   * @param array  $postMeta
   *
   * @return string
   */
  protected function transformTitle (string $title, array $postMeta): string {

    // for edge actions, if we have an associated action, we want to add that
    // to our title for the purposes of display.  this allows something like
    // Knockout Blow (Melee Attack) to be constructed from separate information.

    $associatedAction = $postMeta["associated_action"] ?? "";

    return !empty($associatedAction)
      ? "$title ($associatedAction)"
      : $title;
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
      $this->searchbarElementFactory->produceFilterElement("edge_action_type", "Type", ["boost" => "Boost", "action" => "Action"]),
      $this->searchbarElementFactory->produceFilterElement("cost", "Cost", ["1" => "1", "2" => "2", "3" => "3", "4" => "4", "5" => "5"]),
      $this->searchbarElementFactory->produceFilterElement("book", "Book", $this->extractBooksFromEntries($entries)),
      $this->searchbarElementFactory->produceResetElement("Reset"),
    ];
  }
}
