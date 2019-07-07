<?php

namespace Shadowlab\CheatSheets\Repositories;

use Dashifen\Repository\Repository;
use Shadowlab\ShadowlabException;

/**
 * Class CheatSheet
 *
 * @package Shadowlab\CheatSheets\Repositories
 * @property string $title
 * @property array  $entries
 * @property int    $sheetId
 */
class CheatSheet extends Repository {
  /**
   * @var string
   */
  protected $title = "";

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
   * Sets the title property
   *
   * @param string $title
   *
   * @return void
   */
  protected function setTitle (string $title): void {
    $this->title = $title;
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