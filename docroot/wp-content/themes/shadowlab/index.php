<?php

use Shadowlab\Controller;
use Dashifen\Exception\Exception;
use Shadowlab\Framework\ShadowlabTemplate;

try {
  $controller = new Controller();
  $template = new ShadowlabTemplate($controller->getTheme());
  $template->show("templates/front-page.twig", $controller->isDebug());
} catch (Exception $e) {
  $controller->catcher($e);
}