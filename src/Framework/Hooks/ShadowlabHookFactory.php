<?php

namespace Shadowlab\Framework\Hooks;

use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\WPHandler\Hooks\HookInterface;
use Dashifen\WPHandler\Handlers\HandlerInterface;
use Dashifen\WPHandler\Hooks\Factory\HookFactory;
use Dashifen\WPHandler\Hooks\Factory\HookFactoryInterface;

class ShadowlabHookFactory extends HookFactory implements HookFactoryInterface {
  /**
   * produceHook
   *
   * Returns an implementation of HookInterface to the calling scope.
   *
   * @param string           $hook
   * @param HandlerInterface $object
   * @param string           $method
   * @param int              $priority
   * @param int              $argumentCount
   *
   * @return HookInterface
   * @throws HookException
   */
  public function produceHook (string $hook, HandlerInterface $object, string $method, int $priority = 10, int $argumentCount = 1): HookInterface {

    // all we need to do here is override the base produceHook() function
    // and make it produce ShadowlabHooks instead of the default ones.  then,
    // we just inject this into our handlers and we're good to go!

    return new ShadowlabHook($hook, $object, $method, $priority, $argumentCount);
  }
}
