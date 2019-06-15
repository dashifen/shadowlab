<?php

namespace Shadowlab\Framework\Response;

use Dashifen\Response\ResponseInterface as BaselineResponseInterface;
use Dashifen\Response\View\ViewException;
use Dashifen\Response\ResponseException;

interface ResponseInterface extends BaselineResponseInterface {
  /**
   * setTemplate
   *
   * Uses properties of this object to identify and set the template for
   * this response.  The optional $details parameter can be used to send
   * details about this particular response that might change the identified
   * template.
   *
   * @param string $details
   *
   * @return void
   * @throws ResponseException
   * @throws ViewException
   */
  public function setTemplate(string $details = ""): void;

  /**
   * handleError
   *
   * This application adds the nullable $message parameter to this method.
   * The parent method takes no parameter, but because this one has a default
   * value of null, we're still compatible with its interface.
   *
   * @param string|null $message
   */
  public function handleError (?string $message = null): void;
}