<?php

namespace Shadowlab\CheatSheets\Services;

use Dashifen\WPHandler\Hooks\HookException;

class TypeRegistration extends AbstractShadowlabPluginService {
  /**
   * initialize
   *
   * Uses addAction() and addFilter() to connect WordPress to the methods
   * of this object's child which are intended to be protected.
   *
   * @return void
   * @throws HookException
   */
  public function initialize (): void {
    if (!$this->isInitialized()) {
      $this->addAction("init", "registerTypes");
    }
  }

  /**
   * registerTypes
   *
   * Uses the plugin configuration to register the post types for this plugin.
   *
   * @return void
   */
  protected function registerTypes (): void {

    // before we register the individual types for each entry stored in our
    // configuration, we register the cheat-sheet type itself.  then, we can
    // loop over the post types in our configuration to register all of those.

    $this->registerSheetType();
    foreach ($this->handler->getController()->getPostTypes() as $postType) {
      $postType->registerPostType();
    }
  }

  /**
   * registerSheetType
   *
   * The cheat-sheet type doesn't have a corresponding PostType object
   * because it's different from the other ones.  so, we have a hand-
   * crafted registration method for them here.
   *
   * @return void
   */
  private function registerSheetType (): void {
    $labels = [
      'name'                  => 'Cheat Sheets',
      'singular_name'         => 'Cheat Sheet',
      'menu_name'             => 'Cheat Sheets',
      'name_admin_bar'        => 'Cheat Sheet',
      'archives'              => 'Cheat Sheet Archives',
      'attributes'            => 'Cheat Sheet Attributes',
      'parent_item_colon'     => 'Parent Cheat Sheet',
      'all_items'             => 'All Cheat Sheets',
      'add_new_item'          => 'Add New Cheat Sheets',
      'add_new'               => 'Add New',
      'new_item'              => 'New Cheat Sheet',
      'edit_item'             => 'Edit Cheat Sheet',
      'update_item'           => 'Update Cheat Sheet',
      'view_item'             => 'View Cheat Sheet',
      'view_items'            => 'View Cheat Sheets',
      'search_items'          => 'Search Cheat Sheets',
      'not_found'             => 'Not found',
      'not_found_in_trash'    => 'Not found in Trash',
      'featured_image'        => 'Featured Image',
      'set_featured_image'    => 'Set featured image',
      'remove_featured_image' => 'Remove featured image',
      'use_featured_image'    => 'Use as featured image',
      'insert_into_item'      => 'Add to cheat sheet',
      'uploaded_to_this_item' => 'Uploaded to this cheat sheet',
      'items_list'            => 'Cheat sheets list',
      'items_list_navigation' => 'Cheat sheets list navigation',
      'filter_items_list'     => 'Filter cheat sheets',
    ];

    $args = [
      'label'               => 'Cheat Sheet',
      'supports'            => ['title'],
      'labels'              => $labels,
      'capability_type'     => 'post',
      'menu_position'       => 5,
      'show_in_admin_bar'   => false,
      'show_in_nav_menus'   => false,
      'hierarchical'        => false,
      'has_archive'         => false,
      'rewrite'             => false,
      'show_in_rest'        => false,
      'public'              => false,
      'show_ui'             => false,
      'show_in_menu'        => false,
      'can_export'          => true,
      'exclude_from_search' => true,
      'publicly_queryable'  => true,
      'map_meta_cap'        => true,

      // by changing the create and delete capabilities, we make sure that
      // no visitors to the site can add or remove sheets.  the only way they
      // get messed with is via the sheet registration process built into
      // this plugin.

      'capabilities'        => [
        "create_posts" => "create_cheat_sheet",
        "delete_post"  => "delete_cheat_sheet",
      ],
    ];

    register_post_type('cheat-sheet', $args);
  }
}