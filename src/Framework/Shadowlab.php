<?php

namespace Shadowlab\Framework;

use Throwable;
use Shadowlab\Theme\Theme;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Shadowlab\Framework\Hooks\ShadowlabHookFactory;
use Shadowlab\Framework\Services\ShadowlabServiceFactory;

class Shadowlab {
  /**
   * @var Container;
   */
  private static $container = null;

  /**
   * Container constructor.
   *
   * @throws Exception
   */
  public function __construct () {
    if (is_null(self::$container)) {

      // we only want to configure our league/container once.  so, if it's
      // currently null, we want to do so now.  then, since it's a static
      // property, it'll remain configured even if we re-instantiate our
      // shadowlab/container over and over again.

      $this->configureContainer();
    }
  }

  /**
   * configureContainer
   *
   * Configures our private, static container property.
   *
   * @return void
   * @throws Exception
   */
  private function configureContainer (): void {
    if (!is_null(self::$container)) {

      // we really shouldn't ever get here because we test to be sure that
      // our container property is null before we call this method in the
      // constructor above.  but, just in case something weird happens, we
      // want to throw a tantrum before we spend the time to reconfigure
      // the container itself.

      throw new Exception("Attempt to reconfigure container.",
        Exception::CONTAINER_RECONFIGURED);
    }

    // if we made it here, we're ready to configure our container.  we
    // instantiate it and then delegate anything that we don't tell it how
    // to construct to the auto-wired ReflectionContainer.  for auto-wiring
    // information, see https://container.thephpleague.com/3.x/auto-wiring.

    self::$container = new Container();
    self::$container->delegate(new ReflectionContainer());

    // realistically, using dependency injection here is overkill.  there's
    // just not that many dependencies, but it's nice to have even our minimal
    // dependency configuration all in one place.  the following ones can all
    // be shared resources either because there should be only one of them
    // (the Controller, Theme, and plugin objects) or because they're Factories
    // and thus produce different objects rather than being different in and
    // of themselves.

    self::$container->share(Controller::class);
    self::$container->share(ShadowlabHookFactory::class);
    self::$container->share(ShadowlabServiceFactory::class);

    self::$container->share(Theme::class)
      ->addArgument(ShadowlabHookFactory::class);

    self::$container->share(CheatSheetsPlugin::class)
      ->addArgument(ShadowlabHookFactory::class)
      ->addArgument(ShadowlabServiceFactory::class);
  }

  /**
   * getCheatSheetsPlugin
   *
   * Returns the CheatSheetsPlugin object.
   *
   * @return CheatSheetsPlugin
   */
  public function getCheatSheetsPlugin (): CheatSheetsPlugin {
    return self::$container->get(CheatSheetsPlugin::class);
  }

  /**
   * getTheme
   *
   * Returns the Theme object.
   *
   * @return Theme
   */
  public function getTheme (): Theme {
    return self::$container->get(Theme::class);
  }

  /**
   * getRouter
   *
   * Returns the Router object.
   *
   * @return Router
   */
  public function getRouter (): Router {
    return self::$container->get(Router::class);
  }


  /**
   * isDebug
   *
   * Returns the Theme::isDebug() result as a way to access it when the Theme
   * object hasn't been included into a file's context.
   *
   * @return bool
   */
  public function isDebug (): bool {
    return Theme::isDebug();
  }

  /**
   * debug
   *
   * Print's our $stuff and sometimes quits if $die is set using the Theme
   * object's static debug() method so that we don't have to pull that object
   * into a context where it's not necessary.
   *
   * @param      $stuff
   * @param bool $die
   */
  public function debug ($stuff, bool $die = false): void {
    Theme::debug($stuff, $die);
  }

  /**
   * catcher
   *
   * Executes the Theme::catcher() method as a way to access it when the
   * Theme object hasn't been included into a file's context.
   *
   * @param Throwable $e
   */
  public function catcher (Throwable $e): void {
    Theme::catcher($e);
  }
}