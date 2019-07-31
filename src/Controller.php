<?php

namespace Shadowlab;

use Throwable;
use Shadowlab\Theme\Theme;
use League\Container\Container;
use Symfony\Component\Yaml\Yaml;
use Shadowlab\Repositories\PostType;
use Shadowlab\Repositories\CheatSheet;
use Shadowlab\Repositories\Configuration;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Dashifen\Repository\RepositoryException;
use Shadowlab\Framework\ShadowlabHookFactory;
use Symfony\Component\Yaml\Exception\ParseException;

class Controller implements ControllerInterface {
  /**
   * @var Configuration
   */
  protected static $config;

  /**
   * @var Container
   */
  protected static $container;

  /**
   * @var string
   */
  protected $acfFolder = "";

  /**
   * Controller constructor.
   *
   * @throws ShadowlabException
   * @throws RepositoryException
   */
  public function __construct () {
    if (!(self::$config instanceof Configuration)) {
      $this->setConfig();
    }

    if (!(self::$container instanceof Container)) {
      $this->setContainer();
    }
  }

  /**
   * setConfig
   *
   * Parses the config.yaml file and sets our static config property.
   *
   * @return void
   * @throws RepositoryException
   * @throws ShadowlabException
   */
  protected function setConfig (): void {
    $configFile = ABSPATH . "../config.yaml";

    if (!is_file($configFile)) {
      throw new ShadowlabException("Unable to find config.yaml",
        ShadowlabException::CONFIG_FILE_NOT_FOUND);
    }

    try {

      // YAML is easy to hand type than JSON, but PHP doesn't have a core
      // YAML parser.  so, we'll rely on the one from symfony instead.  then,
      // to help other parts of this plugin "know" what's to be found in our
      // configuration, we'll create a repository out of it and store that.
      // notice that we pass the same set of information to the setters for
      // bot sheets and postTypes; that's intentional.  we simply extract
      // different information from that array for each of our Configuration's
      // properties.

      $yaml = Yaml::parseFile($configFile);
      self::$config = new Configuration([
        "sheets"    => $yaml["sheets"],
        "postTypes" => $yaml["sheets"],
      ]);
    } catch (ParseException $e) {

      // rather than throw symfony's exception, we'll throw ours.  so, here
      // we convert the former to the latter.

      throw new ShadowlabException("Unable to parse config.yaml",
        ShadowlabException::CONFIG_FILE_INVALID);
    }
  }

  /**
   * setContainer
   *
   * Sets the static container property.
   */
  protected function setContainer (): void {
    self::$container = new Container();
    self::$container->add(ShadowlabHookFactory::class);

    self::$container->add(Theme::class)
      ->addArgument(ShadowlabHookFactory::class)
      ->addArgument($this);

    self::$container->add(CheatSheetsPlugin::class)
      ->addArgument(ShadowlabHookFactory::class)
      ->addArgument($this);
  }

  /**
   * getConfig
   *
   * Returns the config property.
   *
   * @return Configuration
   */
  public function getConfig (): Configuration {
    return self::$config;
  }

  /**
   * getSheets
   *
   * Returns the sheets property of our Configuration object
   *
   * @return CheatSheet[]
   */
  public function getSheets (): array {
    return self::$config->sheets;
  }

  /**
   * getPostTypes
   *
   * Returns the sheets property of our Configuration object
   *
   * @return PostType[]
   */
  public function getPostTypes (): array {
    return self::$config->postTypes;
  }

  /**
   * getTheme
   *
   * Returns a Theme object constructed via our static container object.
   *
   * @return Theme
   */
  public function getTheme (): Theme {
    return self::$container->get(Theme::class);
  }

  /**
   * getCheatSheetsPlugin
   *
   * Returns a CheatSheetsPlugin object constructed via our static container
   * object.
   *
   * @return CheatSheetsPlugin
   */
  public function getCheatSheetsPlugin (): CheatSheetsPlugin {
    return self::$container->get(CheatSheetsPlugin::class);
  }

  /**
   * getAcfFolder
   *
   * Returns the ACF assets folder location.
   *
   * @return string
   * @throws ShadowlabException
   */
  public function getAcfFolder (): string {
    if (empty($this->acfFolder)) {
      throw new ShadowlabException("Attempt to use uninitialized ACF folder.",
        ShadowlabException::INVALID_ACF_FOLDER);
    }

    return $this->acfFolder;
  }

  /**
   * setAcfFolder
   *
   * Sets the ACF Folder property.
   *
   * @param string $acfFolder
   *
   * @return void
   * @throws ShadowlabException
   */
  public function setAcfFolder (string $acfFolder): void {
    if (!is_dir($acfFolder)) {
      throw new ShadowlabException("$acfFolder is not a directory.",
        ShadowlabException::INVALID_ACF_FOLDER);
    }

    $this->acfFolder = $acfFolder;
  }

  /**
   * sanitizeUrlSlug
   *
   * Takes a string and makes sure it doesn't have spaces and is in lower
   * case.  So "Foo Bar" would become "foo-bar," for example.
   *
   * @param string $unsanitary
   *
   * @return string
   */
  public static function sanitizeUrlSlug (string $unsanitary): string {
    return strtolower(str_replace(" ", "-", $unsanitary));
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