<?php
/*
Plugin Name: Shadowlab Cheatsheets
Description: The plugin that controls the GM/Player cheatsheets for Shadowlab
Author: David Dashifen Kees
Author URI: https://dashifen.com
Version: 0.0.3
*/

use Shadowlab\Controller;
use Dashifen\Exception\Exception;

require ABSPATH . "../vendor/autoload.php";

try {
  $controller = new Controller();
  $cheatSheets = $controller->getCheatSheetsPlugin();
  $cheatSheets->initialize();
} catch (Exception $e) {
  $controller->catcher($e);
}
