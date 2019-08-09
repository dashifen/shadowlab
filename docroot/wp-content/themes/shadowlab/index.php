<?php

use Shadowlab\Framework\Router;
use Dashifen\Exception\Exception;
use Shadowlab\Framework\Controller;

try {
  $controller = new Controller();
  $router = new Router($controller);
  $template = $router->getTemplate();
  $template->show();
} catch (Exception $e) {
  $controller->catcher($e);
}