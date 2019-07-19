<?php

namespace Shadowlab\CheatSheets\Services;

use Shadowlab\Framework\ShadowlabHook;
use Dashifen\WPHandler\Hooks\HookException;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Dashifen\WPHandler\Services\AbstractPluginService;

/**
 * Class AbstractShadowlabPluginService
 *
 * The only purposes of this abstract class is to define the specific type of
 * our handler so that extensions "know" what that type is and to make sure
 * that we use the extra special ShadowlabHook object rather than the baseline
 * Hook object of the WPHandler library.
 *
 * @package Shadowlab\CheatSheets\Services
 */
abstract class AbstractShadowlabPluginService extends AbstractPluginService {
  /**
   * @var CheatSheetsPlugin
   */
  protected $handler;

  /**
   * addAction
   *
   * Passes its arguments to add_action() and adds $method to the
   * $hooked property.
   *
   * @param string $hook
   * @param string $method
   * @param int    $priority
   * @param int    $arguments
   *
   * @return string
   * @throws HookException
   */
  protected function addAction (string $hook, string $method, int $priority = 10, int $arguments = 1): string {
    $hookIndex = ShadowlabHook::getHookIndex($hook, $this, $method, $priority);
    $this->hooked[$hookIndex] = new ShadowlabHook($hook, $this, $method, $priority, $arguments);
    return add_action($hook, [$this, $method], $priority, $arguments);
  }

  /**
   * removeAction
   *
   * Removes a hooked method from WP core and the record of the hook
   * from our $hooked properties.
   *
   * @param string $hook
   * @param string $method
   * @param int    $priority
   *
   * @return bool
   */
  protected function removeAction (string $hook, string $method, int $priority = 10): bool {
    unset($this->hooked[ShadowlabHook::getHookIndex($hook, $this, $method, $priority)]);
    return remove_action($hook, [$this, $method], $priority);
  }

  /**
   * addFilter
   *
   * Passes its arguments to add_filter() and adds $method to  the
   * $hooked property.
   *
   * @param string $hook
   * @param string $method
   * @param int    $priority
   * @param int    $arguments
   *
   * @return string
   * @throws HookException
   */
  protected function addFilter (string $hook, string $method, int $priority = 10, int $arguments = 1): string {
    $hookIndex = ShadowlabHook::getHookIndex($hook, $this, $method, $priority);
    $this->hooked[$hookIndex] = new ShadowlabHook($hook, $this, $method, $priority, $arguments);
    return add_filter($hook, [$this, $method], $priority, $arguments);
  }

  /**
   * removeFilter
   *
   * Removes a filter from WP and the record of the hooked method
   * from the $hooked property.
   *
   * @param string $hook
   * @param string $method
   * @param int    $priority
   *
   * @return bool
   */
  protected function removeFilter (string $hook, string $method, int $priority = 10): bool {
    unset($this->hooked[ShadowlabHook::getHookIndex($hook, $this, $method, $priority)]);
    return remove_filter($hook, [$this, $method], $priority);
  }
}