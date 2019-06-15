<?php

namespace Shadowlab\Framework\Database;

use Dashifen\Database\DatabaseInterface as BaselineDatabaseInterface;

/**
 * Interface DatabaseInterface
 *
 * @package Shadowlab\Framework\Database
 */
interface DatabaseInterface extends BaselineDatabaseInterface {
  /**
   * getCol
   *
   * Unlike the baseline function, this one has a third parameter to allow
   * for the selection of different columns within the query.  it has a
   * default value, so this method signature remains compatible with its
   * parent's
   *
   * @param string $query
   * @param array  $criteria
   * @param int    $i
   *
   * @return array
   */
  public function getCol (string $query, array $criteria = [], int $i = 0): array;

  /**
   * getRow
   *
   * Unlike the baseline function, this one has a third parameter to allow
   * for the selection of different rows within the query.  it has a default
   * value, so this method signature remains compatible with its parent's
   *
   * @param string $query
   * @param array  $criteria
   * @param int    $i
   *
   * @return array
   */
  public function getRow (string $query, array $criteria = [], int $i = 0): array;
}