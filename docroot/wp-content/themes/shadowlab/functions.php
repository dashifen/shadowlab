<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */

require ABSPATH . "../vendor/autoload.php";

use Aura\Di\ContainerBuilder;
use Dashifen\Response\ResponseException;
use Dashifen\Response\View\ViewException;
use Dashifen\Router\RouterException;
use Shadowlab\Theme\Theme;

/**
 * @var \Dashifen\Exceptionator\ExceptionatorInterface  $exceptionator
 * @var \Dashifen\Router\Route\RouteInterface           $route
 * @var \Dashifen\Action\ActionInterface                $action
 * @var \Shadowlab\Framework\Response\ResponseInterface $response
 */

try {
  $theme = new Theme();
  $theme->initialize();

  /*$cb = new ContainerBuilder();
  $shadowlab = $cb->newConfiguredInstance([
    'Shadowlab\Config\Containers\Database',
    'Shadowlab\Config\Containers\Request',
    'Shadowlab\Config\Containers\Exceptionator',
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
  $response = $action->execute($route->getActionParameter());*/
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
}

//$response->send();