<?php

require ABSPATH . "../vendor/autoload.php";

use Timber\Timber;
use Dashifen\Exception\Exception;
use Shadowlab\Framework\Shadowlab;

try {
  $shadowlab = new Shadowlab();
  $theme = $shadowlab->getTheme();

  // the Timber object needs to know where our twig templates live.  luckily,
  // they live within the theme's stylesheet directory.  thus, we can tell
  // Timber what it needs to know as follows.

  Timber::$locations = $theme->getStylesheetDir() . "/assets/twigs/";
  $theme->initialize();
} catch (Exception $e) {

  // in a perfect world, we'd have something better to do here.  but, this
  // is not that world.  instead, we'll just puke our exception onto the
  // screen and worry about fixing things that way.

  $theme::catcher($e);
}