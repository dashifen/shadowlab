<?php

require ABSPATH . "../vendor/autoload.php";

use Timber\Timber;
use Shadowlab\Controller;
use Shadowlab\Theme\Theme;
use Dashifen\Exception\Exception;

try {

  // here we initialize our theme object and let it add the necessary
  // behaviors we need from it to the overall WordPress ecosystem.

  $controller = new Controller();
  $theme = new Theme($controller);

  Timber::$locations = $theme->getStylesheetDir() . "assets/twigs/";

  $theme->initialize();
} catch (Exception $e) {

  // in a perfect world, we'd have something better to do here.  but, this
  // is not that world.  instead, we'll just puke our exception onto the
  // screen and worry about fixing things that way.

  Theme::catcher($e);
}