<?php

namespace Shadowlab\Framework\Database;

use Dashifen\Database\DatabaseException as BaselineDatabaseException;

class DatabaseException extends BaselineDatabaseException {
  public const INSERT_FORBIDDEN = 1;
  public const UPDATE_FORBIDDEN = 2;
  public const DELETE_FORBIDDEN = 3;
}