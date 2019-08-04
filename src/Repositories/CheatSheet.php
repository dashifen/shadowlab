<?php

namespace Shadowlab\Repositories;

use Shadowlab\Controller;
use Shadowlab\ShadowlabException;
use Dashifen\Repository\Repository;

/**
 * Class CheatSheet
 *
 * @package Shadowlab\CheatSheets\Repositories
 * @property-read string $title
 * @property-read string $slug
 * @property-read array  $entries
 * @property-read int    $sheetId
 */
class CheatSheet extends Repository {
  /**
   * @var string
   */
  protected $title = "";

  /**
   * @var string
   */
  protected $slug = "";

  /**
   * @var array
   */
  protected $entries = [];

  /**
   * @var int
   */
  protected $sheetId = 0;

  /**
   * setTitle
   *
   * Sets the title and slug properties
   *
   * @param string $title
   *
   * @return void
   */
  protected function setTitle (string $title): void {
    $this->setSlug($title);
    $this->title = $title;
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
   * setEntries
   *
   * Sets the entries property
   *
   * @param array $entries
   *
   * @return void
   */
  protected function setEntries (array $entries): void {

    // just in case our entries are not alphabetical, we'll sort them here.
    // we could just manually sort them in the config.yaml file, but that
    // relies on me remembering to do so; this doesn't.

    sort($entries);
    $this->entries = $entries;
  }

  /**
   * setSheetId
   *
   * Sets the sheet ID property
   *
   * @param int $sheetId
   *
   * @return void
   * @throws ShadowlabException
   */
  public function setSheetId (int $sheetId): void {

    // notice that this one is public.  most of the time, our repositories
    // have protected (or private) setters to make their properties read-only
    // after construction.  however, this time, we'll register sheets during
    // the initialization of the plugin so we need a way to record that
    // registration here.  to prevent unnecessary changes, we only allow
    // this change when the current sheet ID is zero, i.e. when it's un-
    // registered.

    if ($this->sheetId !== 0) {
      throw new ShadowlabException("Attempt to overwrite $this->title sheet ID",
        ShadowlabException::SHEET_REREGISTRATION);
    }

    $this->sheetId = $sheetId;
  }
}