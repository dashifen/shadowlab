<?php

require ABSPATH . "../vendor/autoload.php";

use Timber\Timber;
use Dashifen\Exception\Exception;
use Shadowlab\Framework\Controller;

try {
  $controller = new Controller();
  $theme = $controller->getTheme();
  Timber::$locations = $theme->getStylesheetDir() . "/assets/twigs/";
  $theme->initialize();
} catch (Exception $e) {

  // in a perfect world, we'd have something better to do here.  but, this
  // is not that world.  instead, we'll just puke our exception onto the
  // screen and worry about fixing things that way.

  $controller->catcher($e);
}