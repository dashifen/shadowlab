<?php

/** @noinspection PhpFullyQualifiedNameUsageInspection */

use Shadowlab\Theme\Theme;
use Dashifen\Router\RouterInterface;
use Dashifen\Action\ActionInterface;
use Dashifen\Exceptionator\ExceptionatorInterface;
use Shadowlab\Framework\Response\ResponseInterface;

/**
 * @var ExceptionatorInterface $exceptionator
 * @var RouterInterface        $router
 * @var ActionInterface        $action
 * @var ResponseInterface      $response
 */

try {
  $container = (new \Shadowlab\ContainerConfig())->getConfiguredContainer();

  $exceptionator = $container->get(\Shadowlab\Framework\Exceptionator::class);
  $exceptionator->handleExceptions(true);
  $exceptionator->handleErrors(true);

  $router = $container->get(\Shadowlab\Framework\Router::class);
  $route = $router->getRoute();

  echo $route->action;

} catch (Exception $exception) {

  // there's one type of Exception we want to handle with care: a 404 page.
  // everything else just gets dumped to the screen.  notice that we have a
  // nested try/catch block here; that's a bad code smell.  but, it's because,
  // when an exception is thrown, we still need to construct our response.
  // that may, in turn, throw new exceptions.  but, we can safely ignore them
  // (see below).

  /*try {
    $response = $shadowlab->newInstance('Shadowlab\Framework\Response\Response');

    if ($exception instanceof RouterException && $exception->getCode() === RouterException::UNEXPECTED_ROUTE) {

      // the format of this exception's message is Route: <method>;<path>.  we
      // only need the method and path, so we'll extract that as we prepare our
      // response.

      $response->handleNotFound();
      $message = $exception->getMessage();
      $message = substr($message, strpos($message, ":") + 1);
      $response->setDatum("route", $message);
    } else {

      // otherwise, we'll let our exceptionator give us a pretty print out
      // of our exceptional situation and use that as our error message.

      $message = $exceptionator->exceptionHandler($exception, false);
      $response->handleError($message);
    }

    $response->setTemplate();
  } catch (ResponseException | ViewException $exception) {

    // these should never happen; they would indicate a change to either
    // our response or view after it's been compiled.  but, since that
    // hasn't happened yet, we shouldn't ever find ourselves here.  but,
    // if we ever do, we'll just let our exceptionator handle it.

    $exceptionator->exceptionHandler($exception);
  }*/

  Theme::catcher($exception);
}

/*try {
  $theme = new Theme();
  $theme->initialize();
  $theme->setResponse($response);
  $theme->sendResponse();
} catch (HookException $exception) {

  // hook exceptions we can't easily get around.  eventually, we might re-work
  // this into a 400 Bad Request error to be a bit more elegant than simply
  // puking our exception onto the screen, but this works for now.

  Theme::catcher($exception);
}*/
