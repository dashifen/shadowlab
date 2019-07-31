<?php

namespace Shadowlab;

use Dashifen\Exception\Exception;

class ShadowlabException extends Exception {
  public const CONFIG_FILE_NOT_FOUND = 1;
  public const CONFIG_FILE_INVALID = 2;
  public const SHEET_REREGISTRATION = 3;
  public const SHEET_NOT_FOUND = 4;
  public const INVALID_ACF_FOLDER = 5;
  public const ACF_DEFINITION_NOT_FOUND = 6;
}