<?php

namespace Shadowlab\Framework;

use Dashifen\Searchbar\AbstractSearchbar;

class Searchbar extends AbstractSearchbar {
  /**
   * @param array $data
   *
   * the parse function should take an array of data and return a
   * complete searchbar.  the way in which this happens is likely
   * unique to each application that uses this object so we'll leave
   * it abstract here.
   *
   * @return string
   */
  public function parse (array $data): string {

  }
}