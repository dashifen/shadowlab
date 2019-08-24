<?php

namespace Shadowlab\Repositories\Framework;

use Dashifen\Repository\Repository;
use Dashifen\Repository\RepositoryException;
use Shadowlab\Repositories\CheatSheets\PostType;
use Shadowlab\Repositories\CheatSheets\CheatSheet;

/**
 * Class Configuration
 *
 * @package Shadowlab\CheatSheets\Repositories
 * @property-read CheatSheet[] $sheets
 * @property-read PostType[]   $postTypes
 */
class Configuration extends Repository {

  /**
   * @var CheatSheet[]
   */
  protected $sheets = [];

  /**
   * @var PostType[]
   */
  protected $postTypes = [];

  /**
   * setSheets
   *
   * Sets the sheets array, each value of which must be a CheatSheet
   * repository.
   *
   * @param array $sheets
   *
   * @return void
   * @throws RepositoryException
   */
  protected function setSheets (array $sheets = []): void {

    // this method does a lot more work than we would usually put into a
    // setter because our config file doesn't have information related to
    // the WordPress post IDs for our sheets.  thus, we have to find those
    // here to fully initialize our CheatSheet repositories.

    $registeredSheets = $this->getRegisteredSheets();

    foreach ($sheets as $title => $entries) {

      // first, we identify our sheet ID for registered sheets.  if this
      // one isn't registered, we'll stick with the default value of zero
      // so that we'll register it later.  then, we want to get at just
      // the CPT name for each entry within this sheet.  those are the keys
      // of the $entries array at the moment.

      $sheetId = array_search($title, $registeredSheets);

      $this->sheets[] = new CheatSheet([
        "title"   => $title,
        "sheetId" => $sheetId === false ? 0 : $sheetId,
        "entries" => array_keys($entries),
      ]);
    }

    // now, because i'm too lazy to ensure that our config.yaml file has
    // our sheets listed in alphabetical order, we'll sort our sheets property
    // here.  plus, it lets us use the spaceship operator which should be used
    // at least once in every project!

    usort($this->sheets, function (CheatSheet $a, CheatSheet $b): int {
      return $a->title <=> $b->title;
    });
  }

  /**
   * getRegisteredSheets()
   *
   * Returns an array of previously registered sheet titles from the
   * database.  Each title's index is the ID of the
   *
   * @return array
   */
  protected function getRegisteredSheets (): array {
    $sheets = get_posts(["post_type" => "cheat-sheet"]);

    // having gotten each of our sheets above as WP_POST objects, here we
    // create our map of IDs to post titles.  then, we return the map rather
    // that the full post objects.

    foreach ($sheets as $sheet) {
      $map[$sheet->ID] = $sheet->post_title;
    }

    return $map ?? [];
  }

  /**
   * getSheetId
   *
   * Given a sheet's title, find it in our sheets property and return its ID.
   *
   * @param string $title
   *
   * @return int
   */
  public function getSheetId (string $title): int {
    $sheet = $this->getSheet($title);
    return is_null($sheet) ? 0 : $sheet->sheetId;
  }

  /**
   * getSheet
   *
   * Given a sheet's title, returns the CheatSheet object stored within the
   * sheets property for it or null if we can't find it.
   *
   * @param string $title
   *
   * @return CheatSheet|null
   */
  public function getSheet (string $title): ?CheatSheet {
    $title = strtolower($title);

    foreach ($this->sheets as $sheet) {
      if (strtolower($sheet->title) === $title) {
        return $sheet;
      }
    }

    return null;
  }

  /**
   * setPostTypes
   *
   * Sets the post types property
   *
   * @param array $sheets
   *
   * @return void
   * @throws RepositoryException
   */
  protected function setPostTypes (array $sheets = []): void {

    // we actually get the same array here as we did for the setSheets method
    // but we want to focus on different data.  that one focuses on the sheets,
    // here we focus on details about the entries on those sheets.

    foreach ($sheets as $sheetTitle => $entries) {
      foreach ($entries as $type => $data) {

        // each entry in our list is keyed by the post type we want to use
        // for it.  then, the $data array contains singular and plural versions
        // of the noun we use to identify that type on-screen.

        $this->postTypes[] = new PostType([
          "type"     => $type,
          "sheet"    => $this->getSheet($sheetTitle),
          "singular" => $data["singular"],
          "plural"   => $data["plural"],
        ]);
      }
    }
  }

  /**
   * getPostType
   *
   * Given the name of a type, e.g. from the get_post_type() function,
   * returns the PostType object that we store herein that describes it.
   *
   * @param string $type
   *
   * @return PostType|null
   */
  public function getPostType (string $type): ?PostType {
    foreach ($this->postTypes as $postType) {
      if ($postType->type === $type) {
        return $postType;
      }
    }

    return null;
  }
}