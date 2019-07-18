<?php

namespace Shadowlab\CheatSheets\Services;

use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Dashifen\WPHandler\Services\AbstractPluginService;

/**
 * Class AbstractShadowlabPluginService
 *
 * The only purpose of this abstract class is to define the specific type of
 * our handler so that extensions "know" what that type is.
 *
 * @package Shadowlab\CheatSheets\Services
 */
abstract class AbstractShadowlabPluginService extends AbstractPluginService {
  /**
   * @var CheatSheetsPlugin
   */
  protected $handler;
}