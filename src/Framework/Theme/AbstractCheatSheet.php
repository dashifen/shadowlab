<?php

namespace Shadowlab\Framework\Theme;

use Shadowlab\Theme\Theme;
use Shadowlab\Framework\Exception;
use Shadowlab\Repositories\PostType;
use Dashifen\Searchbar\SearchbarInterface;
use Shadowlab\Repositories\CheatSheetEntry;
use Dashifen\Repository\RepositoryException;

abstract class AbstractCheatSheet extends AbstractShadowlabTemplate {
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
    $this->postType = $theme->getController()->getConfig()->getPostType(get_post_type());
    parent::__construct($theme, $getTimberContext);
  }

  /**
   * assignTemplate
   *
   * Sets the template property; named "assign" to make it more clear that this
   * isn't your typical setter.
   *
   * @return void
   */
  protected function assignTemplate (): void {

    // at the moment, all of our cheat sheets use the same twig.  thus, we
    // can implement the abstract-in-our-parent assignTwig() method here and
    // not worry about it in the concrete objects that extend this one.

    $this->template = "templates/cheat-sheet.twig";
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
   * @throws Exception
   */
  public function setContext (array $context = []): void {
    parent::setContext($context);
    $headers = $this->getHeaders();
    $entries = $this->getEntries($headers);

    $this->context["page"] = [
      "type"      => $this->postType->singular,
      "plural"    => $this->postType->plural,
      "searchbar" => $this->getSearchbar($headers, $entries),
      "headers"   => array_keys($headers),
      "entries"   => $entries,
    ];
  }

  /**
   * getHeaders
   *
   * Returns an array of column headings for the display of this cheat sheet.
   *
   * @return array
   * @throws Exception
   */
  private function getHeaders (): array {
    $acfDefinition = $this->theme->getController()->acfDefinitions[$this->postType->singular];

    if (!is_file($acfDefinition->file)) {
      throw new Exception("ACF Definition for {$this->postType->singular} not found.",
        Exception::ACF_DEFINITION_NOT_FOUND);
    }

    $contents = file_get_contents($acfDefinition->file);
    $acfObject = json_decode($contents);

    foreach ($acfObject->fields as $field) {
      if ($field->required) {
        $transformedLabel = $this->transformFieldLabel($field->label);
        $headers[$transformedLabel] = $field->name;
      }
    }

    return $headers ?? [];
  }

  /**
   * transformFieldLabel
   *
   * Transforms the specified label based on the needs of a specific cheat
   * sheet.
   *
   * @param string $label
   *
   * @return string
   */
  abstract protected function transformFieldLabel (string $label): string;

  /**
   * getEntries
   *
   * Returns an array of entries made on this cheat sheet.
   *
   * @param array $headers
   *
   * @return array
   * @throws RepositoryException
   */
  private function getEntries (array $headers): array {
    $posts = get_posts([
      "post_type" => $this->postType->type,
      "orderby"   => "title",
      "order"     => "ASC",
    ]);

    foreach ($posts as $post) {
      $content = apply_filters("the_content", $post->post_content);

      $entries[] = new CheatSheetEntry([
        "title"       => $post->post_title,
        "url"         => get_permalink($post->ID),
        "description" => $this->transformContent($content),
        "fields"      => $this->getFields($headers, $post->ID),
        "page"        => (int) get_field("page", $post->ID),
        "book"        => get_field("book", $post->ID),
      ]);
    }

    return $entries ?? [];
  }

  /**
   * transformContent
   *
   * Transforms the content of a post in case there's more to do to it for
   * our sheet than what WordPress can do via the the_content filter.
   *
   * @param string $content
   *
   * @return string
   */
  abstract protected function transformContent (string $content): string;

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
    foreach ($headers as $transformedLabel => $acfName) {
      $value = $this->transformFieldValue(get_field($acfName, $postId), $transformedLabel);
      $fields[] = $value;
    }

    return $fields ?? [];
  }

  /**
   * transformFieldValue
   *
   * Transforms an ACF field value based on the needs of a specific cheat
   * sheet.  Since such values are of mixed type, we won't really know what
   * types are transformed and returned here.
   *
   * @param mixed  $value
   * @param string $label
   *
   * @return mixed
   */
  abstract protected function transformFieldValue ($value, string $label);

  /**
   * getSearchbar
   *
   * Uses the information in our headers and entries to construct a
   * Searchbar that can help people find the entry they're looking for.
   *
   * @param array $headers
   * @param array $entries
   *
   * @return SearchbarInterface
   */
  abstract protected function getSearchbar(array $headers, array $entries): SearchbarInterface;
}