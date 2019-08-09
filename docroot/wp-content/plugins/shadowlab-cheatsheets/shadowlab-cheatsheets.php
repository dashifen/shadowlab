<?php
/*
Plugin Name: Shadowlab Cheatsheets
Description: The plugin that controls the GM/Player cheatsheets for Shadowlab
Author: David Dashifen Kees
Author URI: https://dashifen.com
Version: 0.0.3
*/

use Dashifen\Exception\Exception;
use Shadowlab\Framework\Controller;

require ABSPATH . "../vendor/autoload.php";

try {
  $controller = new Controller();
  $cheatSheets = $controller->getPlugin();
  $cheatSheets->initialize();
} catch (Exception $e) {
  $controller->catcher($e);
}
