<?php

namespace Shadowlab\Theme;

use Shadowlab\Framework\Controller;
use Shadowlab\Framework\Theme\AbstractCheatSheet;

class TemplateFactory {
  /**
   * @var Controller
   */
  protected $controller;

  /**
   * TemplateFactory constructor.
   *
   * @param Controller $controller
   */
  public function __construct (Controller $controller) {
    $this->controller = $controller;
  }

  /**
   * produceTemplate
   *
   * Uses the specified post type to return the appropriate cheat sheet
   * template object.
   *
   * @param string $postType
   *
   * @return AbstractCheatSheet
   */
  public function produceTemplate(string $postType): AbstractCheatSheet {
    $studlyPostType = $this->classify($postType);
    $templateClassName = $studlyPostType . "CheatSheet";
    $namespaced = '\Shadowlab\Theme\Templates\\' . $templateClassName;
    return new $namespaced($this->controller->getTheme());
  }

  /**
   * classify
   *
   * We get our post type in kabob case (e.g. adept-power) so this
   * transforms them into StudlyCaps and returns.
   *
   * @param string $postType
   *
   * @return string
   */
  public function classify(string $postType): string {

    // this somewhat complex array manipulation splits our parameter on the
    // dashes, then sends the resulting array through the ucfirst() function
    // then joins it back together again with empty strings.  so, adept-power
    // becomes [adept, power] then [Adept, Power] and, finally, AdeptPower.

    return join("", array_map("ucfirst", explode("-", $postType)));
  }
}