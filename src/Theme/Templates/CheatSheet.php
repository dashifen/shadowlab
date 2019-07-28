<?php

namespace Shadowlab\Theme\Templates;

use Shadowlab\Theme\Theme;
use Shadowlab\Repositories\PostType;
use Shadowlab\Framework\ShadowlabTemplate;
use Dashifen\Repository\RepositoryException;

class CheatSheet extends ShadowlabTemplate {
  /**
   * @var PostType
   */
  protected $postType;

  /**
   * AbstractTemplate constructor.
   *
   * @param Theme $theme
   * @param bool  $getTimberContext
   */
  public function __construct (Theme $theme, bool $getTimberContext = false) {
    parent::__construct($theme, $getTimberContext);
    $this->postType = $this->theme->getController()->getConfig()
      ->getPostType(get_post_type());
  }


  /**
   * setContext
   *
   * Sets the context property filling it with data pertinent to the
   * template being displays.  Typically, all the work to do so exists
   * within this method, but data can be passed to it which should be
   * merged with the work performed herein.
   *
   * @param array $context
   *
   * @return void
   * @throws RepositoryException
   */
  public function setContext (array $context = []): void {
    parent::setContext($context);

    $this->context["page"] = [
      "headers" => $this->getHeaders(),
      "entries" => $this->getEntries(),
    ];
  }

  /**
   * getHeaders
   *
   * Returns an array of column headings for the display of this cheat sheet.
   *
   * @return array
   */
  private function getHeaders (): array {


    return $headers ?? [];
  }

  /**
   * getEntries
   *
   * Returns an array of entries made on this cheat sheet.
   *
   * @return array
   */
  private function getEntries (): array {


    return $entries ?? [];
  }
}