<?php

use Shadowlab\Controller;
use Dashifen\WPTemplates\PostException;

try {
  $controller = new Controller();
  $templateFactory = $controller->getTemplateFactory();
  $template = $templateFactory->produceTemplate(get_post_type());
  $template->show("templates/cheat-sheet.twig", $controller->isDebug());
} catch (PostException $e) {
  $controller->catcher($e);
}
