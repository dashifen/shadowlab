<?php
/*
Plugin Name: Shadowlab Cheatsheets
Description: The plugin that controls the GM/Player cheatsheets for Shadowlab
Author: David Dashifen Kees
Author URI: https://dashifen.com
Version: 0.0.3
*/

use Dashifen\Exception\Exception;
use Shadowlab\Framework\Shadowlab;

require ABSPATH . "../vendor/autoload.php";

try {
  $shadowlab = new Shadowlab();
  $cheatSheets = $shadowlab->getCheatSheetsPlugin();
  $cheatSheets->initialize();
} catch (Exception $e) {
  $controller->catcher($e);
}
