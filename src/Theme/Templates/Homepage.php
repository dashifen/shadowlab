<?php

namespace Shadowlab\Theme\Templates;

use Shadowlab\Framework\Theme\AbstractShadowlabTemplate;

class Homepage extends AbstractShadowlabTemplate {
  /**
   * assignTemplate
   *
   * Sets the template property; named "assign" to make it more clear that this
   * isn't your typical setter.
   *
   * @return void
   */
  protected function assignTemplate (): void {
    $this->template = "templates/homepage.twig";
  }
}