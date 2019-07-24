<?php

namespace Shadowlab\CheatSheets\Services;

use Shadowlab\Framework\ShadowlabHook;
use Dashifen\WPHandler\Hooks\HookException;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Dashifen\WPHandler\Services\AbstractPluginService;

/**
 * Class AbstractShadowlabPluginService
 *
 * The only purposes of this abstract class is to define a more specific
 * type hint for our handler property.  This allows the Services within this
 * plugin to "know" that it's a CheatSheetsPlugin object rather than the
 * more general AbstractPluginHandler one.
 *
 * @package Shadowlab\CheatSheets\Services
 */
abstract class AbstractShadowlabPluginService extends AbstractPluginService {
  /**
   * @var CheatSheetsPlugin
   */
  protected $handler;
}