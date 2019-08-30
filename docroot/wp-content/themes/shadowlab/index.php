<?php

use Dashifen\Exception\Exception;
use Shadowlab\Framework\Shadowlab;

try {
  $shadowlab->debug($_SERVER, true);

  // first we instantiate our Controller.  this allows us to instantiate the
  // Router.  with the Router we can get a Template that we use to show the
  // response to this request.

  $shadowlab = new Shadowlab();
  $router = $shadowlab->getRouter();
  $template = $router->getTemplate();
  $template->show();
} catch (Exception $e) {

  // if we run into any problems, we'll just print them to the screen for
  // now.  this lets us fix things as they happen.  not the best solution for
  // a "real world" project, but good enough for my purposes for now.

  $shadowlab->catcher($e);
}