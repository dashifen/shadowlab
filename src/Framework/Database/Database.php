<?php

namespace Shadowlab\Framework\Database;

use Dashifen\Database\AbstractDatabase;
use wpdb;

/**
 * Class Database
 *
 * This object wraps the WordPress WPDB object so that we can use it within
 * the context of the rest of our app without having to refer to it by global
 * variable anywhere but here.
 *
 * @package Shadowlab\Framework
 */
class Database extends AbstractDatabase implements DatabaseInterface {
  /**
   * @var wpdb;
   */
  protected $wpdb;

  /**
   * Database constructor.
   *
   * This one does away with ... well, basically everything that our parent
   * does.  instead, all we need to do is store an internal reference to the
   * WordPress database object.
   *
   * @noinspection PhpMissingParentConstructorInspection
   */
  public function __construct () {
    $this->wpdb = $GLOBALS["wpdb"];
    $this->wpdb->show_errors(true);
  }

  /**
   * isConnected
   *
   * Returns true if we're connected to the database.
   *
   * @return bool
   */
  public function isConnected (): bool {

    // in the WordPress context, if we're here, then we're connected; WP core
    // wouldn't let us get this far if it couldn't talk to the database.

    return true;
  }

  /**
   * getDatabase
   *
   * Returns the name of the WordPress database.
   *
   * @return string|null
   */
  public function getDatabase (): ?string {
    return DB_NAME;
  }

  /**
   * getInsertId
   *
   * Returns the most recently inserted ID in the database.
   *
   * @param string|null $name
   *
   * @return int
   */
  public function getInsertedId (string $name = null): int {
    return $this->wpdb->insert_id;
  }

  /**
   * getError
   *
   * Returns a database error message to the calling scope.
   *
   * @return array|null
   */
  public function getError (): ?array {

    // the wpdb print_error function usually just logs our error and then
    // prints the rest to the screen with printf().  so, we'll start an
    // output buffer, call the print_error() method, and the return its
    // contents and close the buffer all at once.

    ob_start();
    $this->wpdb->print_error();
    return ob_get_clean();
  }

  /**
   * getVar
   *
   * Returns a single value from the database.
   *
   * @param string $query
   * @param array  $criteria
   *
   * @return mixed|void
   */
  public function getVar (string $query, array $criteria = []) {
    $prepared = $this->wpdb->prepare($query, $criteria);
    return $this->wpdb->get_var($prepared);
  }

  /**
   * getCol
   *
   * Returns an array of data from a specific column within a query, usually
   * the first.
   *
   * @param string $query
   * @param array  $criteria
   * @param int    $i
   *
   * @return array
   */
  public function getCol (string $query, array $criteria = [], int $i = 0): array {
    $prepared = $this->wpdb->prepare($query, $criteria);
    return $this->wpdb->get_col($prepared, $i);
  }

  /**
   * getRow
   *
   * Returns an array of data from a specific row within a query, usually
   * the first.
   *
   * @param string $query
   * @param array  $criteria
   * @param int    $i
   *
   * @return array
   */
  public function getRow (string $query, array $criteria = [], int $i = 0): array {
    $prepared = $this->wpdb->prepare($query, $criteria);
    return $this->wpdb->get_row($prepared, ARRAY_A, $i);
  }

  /**
   * getMap
   *
   * Returns an associative array of data from the database mapping column
   * names to column values in each returned row.
   *
   * @param string $query
   * @param array  $criteria
   *
   * @return array
   */
  public function getMap (string $query, array $criteria = []): array {
    $prepared = $this->wpdb->prepare($query, $criteria);
    return $this->wpdb->get_results($prepared, ARRAY_A);
  }

  /**
   * getResults
   *
   * Returns an array of column values from the database.
   *
   * @param string $query
   * @param array  $criteria
   *
   * @return array
   */
  public function getResults (string $query, array $criteria = []): array {
    $prepared = $this->wpdb->prepare($query, $criteria);
    return $this->wpdb->get_results($prepared, ARRAY_N);
  }

  /**
   * insert
   *
   * In this application, all database modifications must be performed via
   * the WordPress dashboard.  Therefore, this method simply throws a
   * DatabaseException so that we get yelled at if we try to use it.
   *
   * @param string $table
   * @param array  $values
   *
   * @return int
   * @throws DatabaseException
   */
  public function insert (string $table, array $values): int {
    throw new DatabaseException("Forbidden action: insert", DatabaseException::INSERT_FORBIDDEN);
  }

  /**
   * update
   *
   * In this application, all database modifications must be performed via
   * the WordPress dashboard.  Therefore, this method simply throws a
   * DatabaseException so that we get yelled at if we try to use it.
   *
   * @param string $table
   * @param array  $values
   * @param array  $criteria
   *
   * @return int
   * @throws DatabaseException
   */
  public function update (string $table, array $values, array $criteria = []): int {
    throw new DatabaseException("Forbidden action: update", DatabaseException::UPDATE_FORBIDDEN);
  }

  /**
   * delete
   *
   * In this application, all database modifications must be performed via
   * the WordPress dashboard.  Therefore, this method simply throws a
   * DatabaseException so that we get yelled at if we try to use it.
   *
   * @param string $table
   * @param array  $criteria
   *
   * @return int
   * @throws DatabaseException
   */
  public function delete (string $table, array $criteria = []): int {
    throw new DatabaseException("Forbidden action: delete", DatabaseException::DELETE_FORBIDDEN);
  }

  /**
   * runQuery
   *
   * This method of the baseline object is intended for complex queries that
   * other methods of this class can't handle, e.g. SELECT FROM INSERT INTO
   * queries.  Since it could be used to modify the database, we're going to
   * forbid its execution here.
   *
   * @param string $query
   * @param array  $criteria
   *
   * @return bool
   * @throws DatabaseException
   */
  public function runQuery (string $query, array $criteria = []): bool {
    throw new DatabaseException("Forbidden action: runQuery");
  }

  /**
   * getStatement
   *
   * Combines the criteria into the query
   *
   * @param string $query
   * @param array  $criteria
   *
   * @return string
   */
  public function getStatement (string $query, array $criteria = []): string {
    return $this->wpdb->prepare($query, $criteria);
  }
}