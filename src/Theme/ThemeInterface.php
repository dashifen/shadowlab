<?php

namespace Shadowlab\Theme;

use Dashifen\WPHandler\Handlers\Themes\ThemeHandlerInterface;
use Shadowlab\Framework\Response\ResponseInterface;

interface ThemeInterface extends ThemeHandlerInterface {
  /**
   * setResponse
   *
   * Informs the Theme object of the response that we're going to display
   * based on the request made of the server.
   *
   * @param ResponseInterface $response
   *
   * @return void
   */
  public function setResponse(ResponseInterface $response): void;

  /**
   * sendResponse
   *
   * Sends the response to the client as HTML for display in a browser.
   *
   * @return void
   */
  public function sendResponse (): void;
}