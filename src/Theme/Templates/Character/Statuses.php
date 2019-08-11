<?php

namespace Shadowlab\Theme\Templates\Character;

use Shadowlab\Framework\Exception;
use Dashifen\Searchbar\SearchbarInterface;
use Dashifen\Searchbar\SearchbarException;
use Shadowlab\Framework\Theme\AbstractCheatSheet;

class Statuses extends AbstractCheatSheet {
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
    return $label === "Maximum Level" ? "Max. Level" : $label;
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
    return $label === "Max. Level" && $value === 0 ? "" : $value;
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
   * @return string
   */
  protected function getSearchbar (array $headers, array $entries): string {

    // the sheet for statuses only needs the item and book filters.  this means
    // that in this case we do not actually need our headers, only the book
    // data in our entries.

    $this->searchbar->addSearch("Status", "status");
    $this->searchbar->addFilter("Book", "book", $this->extractBooksFromEntries($entries));
    $this->searchbar->addReset("Reset");
    return $this->searchbar->getBar();
  }
}
