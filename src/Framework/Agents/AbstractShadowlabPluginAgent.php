<?php

namespace Shadowlab\Framework\Agents;

use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Dashifen\WPHandler\Agents\AbstractPluginAgent;

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
abstract class AbstractShadowlabPluginAgent extends AbstractPluginAgent {
  /**
   * @var CheatSheetsPlugin
   */
  protected $handler;
}