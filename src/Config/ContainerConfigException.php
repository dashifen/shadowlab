<?php

namespace Shadowlab\Config;

use Dashifen\Exception\Exception;

class ContainerConfigException extends Exception {
  public const ROUTE_FILE_NOT_FOUND = 1;
}