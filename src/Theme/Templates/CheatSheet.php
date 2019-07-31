<?php

namespace Shadowlab\Theme\Templates;

use Shadowlab\Theme\Theme;
use Shadowlab\ShadowlabException;
use Shadowlab\Repositories\PostType;
use Shadowlab\Framework\ShadowlabTemplate;
use Shadowlab\Repositories\CheatSheetEntry;
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
   * @throws ShadowlabException
   */
  public function setContext (array $context = []): void {
    parent::setContext($context);

    $this->context["page"] = [
      "headers" => ($headers = $this->getHeaders()),
      "entries" => $this->getEntries($headers),
    ];
  }

  /**
   * getHeaders
   *
   * Returns an array of column headings for the display of this cheat sheet.
   *
   * @return array
   * @throws ShadowlabException
   */
  private function getHeaders (): array {
    $acfFolder = $this->theme->getController()->getAcfFolder();
    $acfDefinition = sprintf("%s/%s.json", $acfFolder, $this->postType->type);

    if (!is_file($acfDefinition)) {
      throw new ShadowlabException("ACF Definition for $this->postType->type not found.",
        ShadowlabException::ACF_DEFINITION_NOT_FOUND);
    }

    $acfObject = json_decode(file_get_contents($acfDefinition));
    foreach ($acfObject->fields as $field) {
      if ($field->required) {
        $headers[$field->label] = $field->name;
      }
    }

    return $headers ?? [];
  }

  /**
   * getEntries
   *
   * Returns an array of entries made on this cheat sheet.
   *
   * @param array $headers
   *
   * @return array
   */
  private function getEntries (array $headers): array {
    $posts = get_posts([
      "post_type" => $this->postType->type,
      "orderby"   => "title",
      "order"     => "ASC",
    ]);

    foreach ($posts as $post) {
      $entries[] = new CheatSheetEntry([
        "title"       => $post->post_title,
        "description" => apply_filters("the_content", $post->post_content),
        "fields"      => $this->getFields($headers, $post->ID),
        "book"        => get_field("book", $post->ID),
        "page"        => get_field("page", $post->ID),
      ]);
    }

    return $entries ?? [];
  }

  /**
   * getFields
   *
   * Returns a map of ACF labels to values for the specified post.
   *
   * @param array $headers
   * @param int   $postId
   *
   * @return array
   */
  private function getFields (array $headers, int $postId): array {
    foreach ($headers as $acfLabel => $acfName) {
      $fields[$acfLabel] = get_field($acfName, $postId);
    }

    return $fields ?? [];
  }
}