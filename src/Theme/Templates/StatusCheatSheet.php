<?php

namespace Shadowlab\Theme\Templates;

use Shadowlab\Framework\Theme\AbstractCheatSheet;

class StatusCheatSheet extends AbstractCheatSheet {
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
}
