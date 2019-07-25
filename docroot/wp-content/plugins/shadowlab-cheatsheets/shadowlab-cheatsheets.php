<?php
/*
Plugin Name: Shadowlab Cheatsheets
Description: The plugin that controls the GM/Player cheatsheets for Shadowlab
Author: David Dashifen Kees
Author URI: https://dashifen.com
Version: 0.0.3
*/

use Shadowlab\Controller;
use Dashifen\WPHandler\Hooks\HookException;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Shadowlab\Framework\ShadowlabHookFactory;

require ABSPATH . "../vendor/autoload.php";

try {
  $controller = new Controller();
  $hookFactory = new ShadowlabHookFactory();
  $cheatSheets = new CheatSheetsPlugin($hookFactory, $controller);
  $cheatSheets->initialize();
} catch (HookException $exception) {
  CheatSheetsPlugin::catcher($exception);
}
