<?php

/** @noinspection PhpUnusedParameterInspection */

namespace Shadowlab\Framework\Response;

use Dashifen\Response\ResponseException;
use Dashifen\Response\View\ViewInterface;
use Dashifen\Response\View\ViewException;
use Zend\HttpHandlerRunner\Emitter\EmitterInterface;
use Dashifen\Response\Factory\ResponseFactoryInterface;
use Dashifen\Response\AbstractResponse as BaselineAbstractResponse;

/**
 * Class AbstractResponse
 *
 * Implements the four abstract response methods of its parents to
 * throw exceptions. Children of this method should override these
 * methods to do something more useful than that.
 *
 * @package Shadowlab\Framework\Response
 */
class Response extends BaselineAbstractResponse {
  public const ACTION_TYPES = ["create", "read", "update", "delete"];
  public const RESPONSE_STATES = ["success", "failure", "error", "notfound"];

  /**
   * @var string
   */
  protected $actionType = "read";   // WordPress handles CUD at the moment

  /**
   * @var string
   */
  protected $responseState = "";

  /**
   * @var string
   */
  protected $templateName = "";

  /**
   * AbstractResponse constructor.
   *
   * Sets a blank default error response (since, hopefully, there won't be
   * an error to share with the visitor).
   *
   * @param ViewInterface            $view
   * @param EmitterInterface         $emitter
   * @param ResponseFactoryInterface $responseFactory
   * @param string                   $rootPath
   *
   * @throws ResponseException
   */
  public function __construct (
    ViewInterface $view,
    EmitterInterface $emitter,
    ResponseFactoryInterface $responseFactory,
    string $rootPath = ""
  ) {
    parent::__construct($view, $emitter, $responseFactory, $rootPath);

    // most of our views have a place to put an error.  rather than having to
    // remember to fill it with nothing whenever there hasn't been an error,
    // we'll just set it to be blank by default; we can always change it later.

    $this->setDatum("error", "");
  }

  /**
   * setActionType
   *
   * Sets the action property after making sure it's in our ACTION_TYPES.
   *
   * @param string $action
   *
   * @return void
   * @throws ResponseException
   */
  public function setActionType (string $action): void {
    $lowerAction = strtolower($action);

    if (!in_array($lowerAction, self::ACTION_TYPES)) {
      throw new ResponseException("Unknown action type: $action");
    }

    $this->actionType = $action;
  }

  /**
   * setResponseState
   *
   * This protected setter ensures that only from the response handling
   * messages do we ever mess with our type.  It also checks to be sure that
   * we only use types that we expect (based on the constant above).
   *
   * @param string $responseState
   *
   * @throws ResponseException
   */
  protected function setResponseState (string $responseState): void {
    $lowerResponseState = strtolower($responseState);

    if (!in_array($lowerResponseState, self::RESPONSE_STATES)) {
      throw new ResponseException("Unexpected response state: $responseState");
    }

    $this->responseState = $lowerResponseState;
  }

  /**
   * handleSuccess
   *
   * Sets our response state to success.
   *
   * @return void
   * @throws ResponseException
   */
  public function handleSuccess (): void {
    $this->setResponseState("success");
  }

  /**
   * handleFailure
   *
   * Sets our response state to failure.
   *
   * @return void
   * @throws ResponseException
   */
  public function handleFailure (): void {
    $this->setResponseState("failure");
  }

  /**
   * handleNotFound
   *
   * Sets our response state to notfound.
   *
   * @return void
   * @throws ResponseException
   */
  public function handleNotFound (): void {
    $this->setResponseState("notfound");
    $this->setStatusCode(404);

    // we want to completely override whatever data might have been sent
    // here by now.  thus, rather than rely on setData and setDatum, we're
    // just going to directly mess with the data property here.

    $this->data = [
      "title"        => "Critical Glitch - Paydata Not Found",
      "heading"      => "Critical Glitch",
      "errorMessage" => $this->getErrorMessage(),
      "httpError"    => "Not Found",
    ];
  }

  /**
   * getErrorMessage
   *
   * Uses our status code to return one of a few canned error messages.
   *
   * @param string|null $message
   *
   * @return string
   */
  protected function getErrorMessage (?string $message = null): string {
    switch ($this->statusCode) {
      case 401:
        return "You have not yet gained access to this host.";

      case 404:
        return "The paydata you requested could not be found.";

      default:

        // if we have a non-null $message, we'll use it.  but, if not,
        // the null coalescing operator will return it's r-value and
        // we'll display the error message equivalent of ¯\_(ツ)_/¯

        return $message ?? "An unknown error has occurred.";
    }
  }

  /**
   * handleError
   *
   * Sets our response state to error and also handles the status codes.
   *
   * @param string|null $message
   *
   * @return void
   * @throws ResponseException
   */
  public function handleError (?string $message = null): void {
    $this->setResponseState("error");

    // in addition to setting our response state, we also want to handle
    // the information our response needs to know about status codes.  if
    // our data indicates that this is an http error, we can use that to
    // continue.

    if ($this->isHttpError()) {

      // now that we know it's an http error, we'll get the status code
      // that corresponds to it.  if this method returns null, then the
      // code could not be identified and we'll default to 400 (Bad
      // Request).

      $phrase = $this->getStatusPhrase();
      $statusCode = $this->getStatusCode($phrase) ?? 400;
      $this->setStatusCode($statusCode);
    }

    // finally, regardless of what type of error this is, we want to make
    // sure we have an error message to display.  if we already have one,
    // we're good to go, but if we don't, then we'll set one here.

    if (!$this->hasErrorMessage()) {
      $this->setDatum("errorMessage", $this->getErrorMessage($message));
    }
  }

  /**
   * isHttpError
   *
   * Returns true if $data informs us that this is an http error.
   *
   * @return bool
   */
  private function isHttpError (): bool {
    return isset($this->data["httpError"]);
  }

  /**
   * getStatusPhrase
   *
   * Digs into our data property and extracts the status phrase from it.
   *
   * @return string
   */
  private function getStatusPhrase (): string {
    return $this->data["httpError"];
  }

  /**
   * hasErrorMessage
   *
   * Returns true if our data property has an errorMessage within it.
   * It's not enough simply to have the index, that index must also be
   * defined (i.e. not empty).
   *
   * @return bool
   */
  private function hasErrorMessage (): bool {
    return isset($data["errorMessage"]) && !empty($data["errorMessage"]);
  }

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
  public function setTemplate (string $details = ""): void {

    // first we grab and "remember" our template's name.  then, we can
    // do the important stuff:  tell our parent what the template is so
    // that we can compile our view later.

    $this->templateName = $this->getTemplateName($details);
    $this->setContent($this->templateName);
  }

  /**
   * getTemplateName
   *
   * Uses the $data and $action parameters to identify the template file that
   * we use to display this response and returns its name.  This method is
   * private; if children need to change what template is identified, that's
   * what the transformTemplateName() method is for.
   *
   * @param string $details
   *
   * @return string
   * @throws ResponseException
   */
  private function getTemplateName (string $details): string {

    // if we don't have a response type yet, then we've got to quit.  it's
    // the response type, along with our $data and $action parameters that
    // identify our template.

    if (empty($this->responseState)) {
      throw new ResponseException("Response type required to identify template");
    }

    $template = "";
    switch ($this->responseState) {
      case "success":

        // in prior versions of this app, we had to handle all four
        // successful CRUD operations.  this time, WordPress will handle
        // create, update, and delete, so all we need to handle here is
        // read.  the template we use when reading is based on $data.

        $template = $this->hasMultipleRecords()
          ? "records/collection.twig"
          : "records/single.twig";

        break;

      case "error":
      case "notfound":

        // the two types of errors we worry about are http errors and
        // an error we encounter within the application itself.  the $data
        // parameter can help us here.

        $template = $this->isHttpError()
          ? "errors/http.twig"
          : "errors/app.twig";

        break;

      case "failure":
        $template = "failure.twig";
        break;
    }

    return $this->transformTemplateName($template, $details);
  }

  /**
   * hasMultipleRecords
   *
   * If our data property has a count index and if that index is greater
   * than one, we return true.
   *
   * @return bool
   */
  private function hasMultipleRecords (): bool {

    // we can use the null coalescing operator to either use the count
    // index within our $data property or default to the assumption that
    // we have a count of one.  then, if that count is greater than one,
    // we return true.

    $count = $this->data["count"] ?? 1;
    return $count > 1;
  }

  /**
   * transformTemplate
   *
   * Usually, we can use the $template identified above directly, but
   * in case a specific part of this app needs to mess with that, those
   * parts can override this method.  by default, we don't need the
   * $details parameter, but other parts of the app might.
   *
   * @param string $template
   * @param string $details
   *
   * @return string
   */
  protected function transformTemplateName (string $template, string $details): string {
    return $template;
  }
}