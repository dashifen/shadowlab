<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */

require ABSPATH . "../vendor/autoload.php";

use Aura\Di\ContainerBuilder;
use Dashifen\Response\View\ViewException;
use Dashifen\Router\RouterException;
use Dashifen\Response\ResponseException;
use Aura\Di\Exception\SetterMethodNotFound;

/**
 * @var \Dashifen\Exceptionator\ExceptionatorInterface  $exceptionator
 * @var \Dashifen\Router\Route\RouteInterface           $route
 * @var \Dashifen\Action\ActionInterface                $action
 * @var \Shadowlab\Framework\Response\ResponseInterface $response
 */

try {
  $cb = new ContainerBuilder();
  $shadowlab = $cb->newConfiguredInstance([

  ]);

  // we'll set up our exceptionator class as the handler of last resort for
  // both errors and exceptions.  this'll print them on-screen in a readable
  // way for easier debugging.

  $exceptionator = $shadowlab->newInstance('Dashifen\Exceptionator\Exceptionator');
  $exceptionator->handleExceptions(true);
  $exceptionator->handleErrors(true);

  // now, it's time to figure out where we are.  since all URLs on the site
  // get to us here and since we're ignoring the WordPress router from this
  // point forward, we'll have to use our router to determine what action to
  // take here.  one we have our action, we execute it, receive our response
  // and send it to the visitor.

  $route = $shadowlab->newInstance('Dashifen\Router\Router')->route();
  $action = $shadowlab->newInstance($route->getAction());
  $response = $action->execute($route->getActionParameter());
} catch (RouterException $routerException) {

  // there's a type of router exception that we want to handle here: an
  // unexpected route.  if we have that, we'll force our 404 response here.
  // we need this because we've completely abandoned the WP router for this
  // theme so we can't rely on it to take us to a 404.php template.  any
  // other type of RouterException we'll pass on as general error response.
  // the false flag overrides the default behavior (dying) and returns the
  // message instead.

  try {
    $response = $shadowlab->newInstance('Shadowlab\Framework\Response\Response');

    if ($routerException->getCode() === RouterException::UNEXPECTED_ROUTE) {

      // the format of this exception's message is Route: <method>;<path>.  we
      // only need the method and path, so we'll extract that as we prepare our
      // response.

      $response->handleNotFound();
      $message = $routerException->getMessage();
      $message = substr($message, strpos($message, ":") + 1);
      $response->setDatum("route", $message);
      $response->setTemplate();
    } else {

      // otherwise, this is a general error, not a 404, and we'll tell it
      // to handle the error case.  but, we'll want to send it the message
      // to print based on this exception.  the false flag we send to the
      // exceptionator will force it to return the message it builds instead
      // of dying.

      $message = $exceptionator->exceptionHandler($routerException, false);
      $response->handleError($message);
      $response->setTemplate();
    }
  } catch (ResponseException | ViewException $exception) {

    // nested try/catch blocks is a pretty bad code smell, bit in this case
    // both the response and view exception that could be thrown is about
    // altering either of them after the view is compiled.  since we know
    // that hasn't happened yet, we can safely ignore these exceptions at
    // this time.

  }
} catch (SetterMethodNotFound $setterNotFoundException) {

  // like above, here we can't do much other than  print our message.  we'll
  // get it with our exceptionator and then pass it to our response.

  try {
    $message = $exceptionator->exceptionHandler($setterNotFoundException, false);
    $response = $shadowlab->newInstance('Shadowlab\Framework\Response\Response');
    $response->handleError($message);
    $response->setTemplate();
  } catch (ResponseException | ViewException $viewException) {

    // also like above, we don't have to worry about these.  we still haven't
    // compiled our responses, so it's unnecessary for us do anything here
    // since they won't be thrown at this time.

  }
}

$response->send();