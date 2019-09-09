<?php

namespace Shadowlab\Framework\Templates;

use Dashifen\WPTemplates\PostException;

class ShadowlabTemplateException extends PostException {

  // to avoid colliding with constants defined by our parent, we start ours
  // at 1000.  at the time of this comment, PostException only defines two
  // constants so this is a stupendous level of overkill but whatever.

  public const CANNOT_IDENTIFY_POST_TYPE = 1000;
}