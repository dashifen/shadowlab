<?php

namespace Shadowlab\Framework;

use Throwable;
use Shadowlab\Theme\Theme;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Shadowlab\Framework\Hooks\ShadowlabHookFactory;
use Dashifen\WPTemplates\Templates\TemplateInterface;
use Dashifen\WPHandler\Hooks\Collection\Factory\HookCollectionFactory;
use Shadowlab\CheatSheets\Agents\CollectionFactory\CheatSheetAgentCollectionFactory;

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
    self::$container->share(CheatSheetAgentCollectionFactory::class)
      ->addMethodCall("registerAgents");

    // our Theme doesn't use Agents at this time.  so, it doesn't need an
    // AgentCollectionFactory at all.  we can just skip that dependency here
    // since it's nullable at the level of the AbstractHandler from which
    // our Theme descends.

    self::$container->share(Theme::class)->addArguments([
      ShadowlabHookFactory::class,
      HookCollectionFactory::class,
      Controller::class,
    ]);

    // our CheatSheetPlugin, on the other hand, does use agents and, therefore,
    // needs an AgentCollectionFactory.  we've extended on for our use here,
    // and we told our container how to construct it above.

    self::$container->share(CheatSheetsPlugin::class)->addArguments([
      ShadowlabHookFactory::class,
      HookCollectionFactory::class,
      CheatSheetAgentCollectionFactory::class,
      Controller::class
    ]);

    // our Router actually needs a reference to this object; that's because
    // it needs to know how to construct the various templates within this
    // application.  doing so requires access to this object's getTemplate()
    // method.  so, we'll add this object as an argument to the Router as
    // follows.

    self::$container->share(Router::class)->addArgument($this);
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
   * getTemplate
   *
   * Given the fully namespaced template object's name, return one of them.
   *
   * @param string $template
   *
   * @return TemplateInterface
   */
  public function getTemplate (string $template): TemplateInterface {
    return self::$container->get($template);
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