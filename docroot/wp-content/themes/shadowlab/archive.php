<?php

use Shadowlab\Controller;
use Dashifen\WPTemplates\PostException;
use Shadowlab\Theme\Templates\CheatSheet;

try {
  $controller = new Controller();
  $template = new CheatSheet($controller->getTheme());
  $template->show("templates/cheat-sheet.twig", $controller->isDebug());
} catch (PostException $e) {
  $controller->catcher($e);
}
