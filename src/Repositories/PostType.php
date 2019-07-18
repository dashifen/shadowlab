<?php

namespace Shadowlab\Repositories;

use Shadowlab\Controller;
use Dashifen\Repository\Repository;

/**
 * Class PostType
 *
 * @package Shadowlab\CheatSheets\Repositories
 * @property string     $type
 * @property string     $singular
 * @property string     $plural
 * @property string     $slug
 * @property int        $sheetId
 * @property CheatSheet $sheet
 */
class PostType extends Repository {
  /**
   * @var string
   */
  protected $type = "";

  /**
   * @var string
   */
  protected $singular = "";

  /**
   * @var string
   */
  protected $plural = "";

  /**
   * @var string
   */
  protected $slug = "";

  /**
   * @var CheatSheet
   */
  protected $sheet;

  /**
   * setType
   *
   * Sets the type property
   *
   * @param string $type
   *
   * @return void
   */
  protected function setType (string $type): void {
    $this->type = $type;
  }

  /**
   * setSingular
   *
   * Sets the singular property
   *
   * @param string $singular
   *
   * @return void
   */
  protected function setSingular (string $singular): void {
    $this->singular = $singular;
  }

  /**
   * setPlural
   *
   * Sets the plural and slug properties
   *
   * @param string $plural
   *
   * @return void
   */
  protected function setPlural (string $plural): void {
    $this->setSlug($plural);
    $this->plural = $plural;
  }

  /**
   * setSlug
   *
   * Sets the slug property
   *
   * @param string $slug
   *
   * @return void
   */
  protected function setSlug (string $slug): void {
    $this->slug = Controller::sanitizeUrlSlug($slug);
  }

  /**
   * setSheetId
   *
   * Sets the sheet ID property
   *
   * @param CheatSheet $sheet
   *
   * @return void
   */
  protected function setSheet (CheatSheet $sheet): void {
    $this->sheet = $sheet;
  }

  /**
   * registerPostType
   *
   * We want each type described here to know how to register itself within
   * the WordPress ecosystem.  calling this method will use the properties
   * above to
   *
   * @return void
   */
  public function registerPostType (): void {
    $labels = [
      "name"                  => $this->plural,
      "singular_name"         => $this->singular,
      "menu_name"             => $this->plural,
      "name_admin_bar"        => $this->singular,
      "archives"              => "$this->singular Archives",
      "attributes"            => "$this->singular Attributes",
      "parent_item_colon"     => "Parent $this->singular",
      "all_items"             => "All $this->plural",
      "add_new_item"          => "Add New $this->plural",
      "add_new"               => "Add New",
      "new_item"              => "New $this->singular",
      "edit_item"             => "Edit $this->singular",
      "update_item"           => "Update $this->singular",
      "view_item"             => "View $this->singular",
      "view_items"            => "View $this->plural",
      "search_items"          => "Search $this->plural",
      "not_found"             => "Not found",
      "not_found_in_trash"    => "Not found in Trash",
      "featured_image"        => "Featured Image",
      "set_featured_image"    => "Set featured image",
      "remove_featured_image" => "Remove featured image",
      "use_featured_image"    => "Use as featured image",
      "insert_into_item"      => "Add to $this->singular",
      "uploaded_to_this_item" => "Uploaded to this $this->singular",
      "items_list"            => "$this->plural list",
      "items_list_navigation" => "$this->plural list navigation",
      "filter_items_list"     => "Filter $this->plural",
    ];

    $args = [
      "labels"              => $labels,
      "label"               => $this->type,
      "supports"            => ["title", "editor"],
      "rewrite"             => ["slug" => $this->slug],
      "capability_type"     => "post",
      "hierarchical"        => false,
      "exclude_from_search" => false,
      "show_in_menu"        => false,
      "show_in_nav_menus"   => false,
      "show_in_admin_bar"   => true,
      "public"              => true,
      "show_ui"             => true,
      "can_export"          => true,
      "has_archive"         => true,
      "publicly_queryable"  => true,
      "menu_position"       => 5,
    ];

    register_post_type($this->type, $args);
  }

  /**
   * getSlug
   *
   * To keep the code clean above, this function simply constructs and returns
   * the slug we want to use for this post type.  note that this getter will
   * be automatically executed when we use the arrow operator to access the
   * slug property of this object.  that's why it can be protected and still
   * do the work we want it to do.
   *
   * @return string
   */
  protected function getSlug (): string {

    // we want our post type archives/entries to put the name of the sheet
    // on which they are shown prior to their slug.  since it's possible that
    // sheet names or plural type names have spaces in them, we'll make them
    // url-friendly before we use them.

    return sprintf("%s/%s", $this->sheet->slug, $this->slug);
  }
}