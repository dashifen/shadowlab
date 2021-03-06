<?php

namespace Shadowlab\Framework\Hooks;

use Dashifen\WPHandler\Hooks\Hook;
use Dashifen\WPHandler\Hooks\HookException;
use Shadowlab\CheatSheets\Agents\MenuModificationAgent;

class ShadowlabHook extends Hook {
  /**
   * setMethod
   *
   * A quick tweak to allow "show*" methods as part of the MenuModifier
   * to be allowed.
   *
   * @param string $method
   *
   * @throws HookException
   */
  public function setMethod (string $method): void {
    try {
      parent::setMethod($method);
    } catch (HookException $e) {

      // by default, if this gets thrown and it's a METHOD_NOT_FOUND error,
      // we'll see if it's a "show" method of the MenuModifier service.  if
      // so, we're actually fine.  otherwise, we'll just re-throw the
      // exception to be handled elsewhere.

      if (
        $e->getCode() === HookException::METHOD_NOT_FOUND &&
        $this->object instanceof MenuModificationAgent &&
        substr($method, 0, 4) === "show"
      ) {
        $this->method = $method;
      } else {
        throw $e;
      }
    }
  }
}