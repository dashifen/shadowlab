<?php

namespace Shadowlab\CheatSheets\Services;

use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\WPHandler\Services\AbstractPluginService;
use WP_Post;

class ACFModifications extends AbstractPluginService {
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
    $this->addAction("save_post_acf-field-group", "exportCustomFieldGroups", 1000, 2);
  }

  /**
   * exportCustomFieldGroups
   *
   * When an ACF field group post is saved, this exports the JSON for it
   * so that we can commit it to the git repo.  That way we can rollback
   * changes if we mess something up, which, of course, we never do.
   *
   * @param int     $postId
   * @param WP_Post $post
   *
   * @return void
   */
  protected function exportCustomFieldGroups (int $postId, WP_Post $post) {
    list($acfName, $filename) = $this->getFieldGroupDetails($postId);

    if (empty($acfName)) {
      return;
    }

    $contents = $this->getFieldGroupContents($acfName);
    $filename = sprintf("%s/field-groups/%s.json", $this->handler->getPluginDir(), $filename);
    file_put_contents($filename, $contents);
  }

  /**
   * getFieldGroupDetails
   *
   * Given the ID of a post in the database, get the information
   * needed to get the JSON data about the ACF field group it
   * references.
   *
   * @param int $postId
   *
   * @return array
   */
  protected function getFieldGroupDetails (int $postId) {
    global $wpdb;

    // here, we need to get the post data for the $postId that was
    // passed here.  we could use the WP_Query object to do that, but
    // it has a lot of overhead.  instead, we'll just do it live.
    // some IDEs flag the ID=%d syntax for the wpdb->prepare() method
    // as an error.  so, we'll make a variable, $d, that contains
    // that string and use it below.

    $d = "%d";

    $statement = $wpdb->prepare(
    /** @lang text */

      "SELECT post_name, post_excerpt FROM $wpdb->posts WHERE ID=$d",
      $postId
    );

    return $wpdb->get_row($statement, ARRAY_N);
  }

  /**
   * getFieldGroupContents
   *
   * Uses ACF functions to get information about the specified group
   * and returns that information as a JSON string.
   *
   * @param string $acfName
   *
   * @return string
   */
  protected function getFieldGroupContents (string $acfName) {
    $fieldGroup = acf_get_field_group($acfName);

    if (!empty($fieldGroup)) {
      $fieldGroup['fields'] = acf_get_fields($fieldGroup);
      $json = acf_prepare_field_group_for_export($fieldGroup);
    }

    return json_encode($json ?? "");
  }
}