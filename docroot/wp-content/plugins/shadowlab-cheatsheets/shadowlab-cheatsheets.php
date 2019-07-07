<?php
/*
Plugin Name: Shadowlab Cheatsheets
Description: The plugin that controls the GM/Player cheatsheets for Shadowlab
Author: David Dashifen Kees
Author URI: https://dashifen.com
Version: 0.0.3
*/

use Shadowlab\CheatSheets\CheatSheetsPlugin;

require ABSPATH . "../vendor/autoload.php";

$cheatSheets = new CheatSheetsPlugin();
$cheatSheets->initialize();