<?php

namespace Shadowlab\Framework;

use Dashifen\Exception\Exception as DashifenException;

class Exception extends DashifenException {
  public const CONTAINER_RECONFIGURED   = 1;
  public const CONFIG_FILE_NOT_FOUND    = 2;
  public const CONFIG_FILE_INVALID      = 3;
  public const SHEET_REREGISTRATION     = 4;
  public const SHEET_NOT_FOUND          = 5;
  public const INVALID_ACF_FOLDER       = 6;
  public const ACF_DEFINITION_NOT_FOUND = 7;
}