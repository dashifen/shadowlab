<?php

namespace Shadowlab\CheatSheets\Services;

use DirectoryIterator;
use Shadowlab\Framework\Exception;
use Shadowlab\Repositories\ACFDefinition;
use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\Repository\RepositoryException;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Shadowlab\Framework\Services\AbstractShadowlabPluginService;

class ACFModifier extends AbstractShadowlabPluginService {
  /**
   * @var CheatSheetsPlugin
   */
  protected $handler;

  /**
   * @var string
   */
  private $fieldGroupFolder = "";

  /**
   * AbstractPluginService constructor.
   *
   * @param CheatSheetsPlugin $handler
   *
   * @throws Exception
   * @throws RepositoryException
   */
  public function __construct (CheatSheetsPlugin $handler) {
    $this->fieldGroupFolder = sprintf("%s/field-groups", $handler->getPluginDir());
    $handler->getController()->setAcfFolder($this->fieldGroupFolder);
    parent::__construct($handler);
  }

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

      // if the current user is an administrator, we want to automatically
      // (re)import field groups to be sure that this installation is up to
      // date.  since Dash is likely the only admin, this should only fire
      // when it's them logging in.


      /*if (in_array("administrator", wp_get_current_user()->roles)) {
        $this->addAction("admin_init", "importFieldGroups");
      }*/

      $this->addAction("save_post_acf-field-group", "exportCustomFieldGroups", 1000);
    }
  }

  /**
   * importFieldGroups
   *
   * Automatically imports field groups that either don't exist or are out
   * of date.
   *
   * @return void
   * @throws RepositoryException
   */
  protected function importFieldGroups () {
    if (!get_transient(__FUNCTION__)) {
      $fgRegistered = $this->getRegisteredFieldGroups();
      $fgDefinitions = $this->getFieldGroupDefinitions();
      foreach ($fgDefinitions as $fgTitle => $fgDefinition) {
        $newField = !array_key_exists($fgTitle, $fgRegistered);

        // if this isn't a new file, then we need to see if the date the
        // post last modified in the database is less than the most recent
        // modification to the definition file.  luckily, our $fgRegistered
        // map is titles to dates, so we can determine this easily as
        // follows.

        $oldDefinition = !$newField && $fgRegistered[$fgTitle] < $fgDefinition->lastModified;
        if ($newField || $oldDefinition) {

          // the acf import function wants an array as its parameter, not
          // the filename.  so, here we read it and decode it before calling
          // the core ACF function to handle the import.

          $contents = file_get_contents($fgDefinition->file);
          $fgArray = json_decode($contents, true);
          acf_add_local_field_group($fgArray);
        }
      }

      // HOUR_IN_SECONDS * 12 means we'll do this roughly twice a day.
      // that should be enough to catch changes regardless of where this
      // system gets worked on.

      set_transient(__FUNCTION__, time(), HOUR_IN_SECONDS * 12);
    }
  }

  /**
   * getFieldGroupDefinitions
   *
   * Returns an array of field group titles mapped to the JSON file that
   * defines that group.
   *
   * @return ACFDefinition[]
   * @throws RepositoryException
   */
  private function getFieldGroupDefinitions (): array {
    $files = new DirectoryIterator($this->fieldGroupFolder);

    foreach ($files as $file) {
      if ($file->getExtension() === "json") {
        $file = $file->getPathname();
        $definitions[] = new ACFDefinition([
          "title"        => json_decode(file_get_contents($file))->title,
          "lastModified" => filemtime($file),
          "file"         => $file,
        ]);
      }
    }

    return $definitions ?? [];
  }

  /**
   * getRegisteredFieldGroups
   *
   * Returns an array of post titles mapped to post modification dates.
   *
   * @return array
   */
  private function getRegisteredFieldGroups (): array {
    $fieldGroups = get_posts(["post_type" => "acf-field-group"]);
    foreach ($fieldGroups as $fieldGroup) {
      $registered[$fieldGroup->post_title] = strtotime($fieldGroup->post_modified);
    }

    return $registered ?? [];
  }

  /**
   * exportCustomFieldGroups
   *
   * When an ACF field group post is saved, this exports the JSON for it
   * so that we can commit it to the git repo.  That way we can rollback
   * changes if we mess something up, which, of course, we never do.
   *
   * @param int $postId
   *
   * @return void
   */
  protected function exportCustomFieldGroups (int $postId) {
    list($acfName, $filename) = $this->getFieldGroupDetails($postId);

    if (empty($acfName)) {
      return;
    }

    $contents = $this->getFieldGroupContents($acfName);
    $filename = $this->fieldGroupFolder . sprintf("/%s.json", $filename);
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
  private function getFieldGroupDetails (int $postId) {
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
  private function getFieldGroupContents (string $acfName) {
    $fieldGroup = acf_get_field_group($acfName);

    if (!empty($fieldGroup)) {
      $fieldGroup['fields'] = acf_get_fields($fieldGroup);
      $json = acf_prepare_field_group_for_export($fieldGroup);
    }

    return json_encode($json ?? "", JSON_PRETTY_PRINT);
  }
}