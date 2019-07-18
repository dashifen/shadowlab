<?php

namespace Shadowlab;

use Symfony\Component\Yaml\Yaml;
use Shadowlab\Repositories\PostType;
use Shadowlab\Repositories\CheatSheet;
use Shadowlab\Repositories\Configuration;
use Dashifen\Repository\RepositoryException;
use Symfony\Component\Yaml\Exception\ParseException;

class Controller implements ControllerInterface {
  /**
   * @var Configuration
   */
  protected $config;

  /**
   * Controller constructor.
   *
   * @throws ShadowlabException
   * @throws RepositoryException
   */
  public function __construct () {
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
      $this->config = new Configuration([
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
   * getConfig
   *
   * Returns the config property.
   *
   * @return Configuration
   */
  public function getConfig (): Configuration {
    return $this->config;
  }

  /**
   * getSheets
   *
   * Returns the sheets property of our Configuration object
   *
   * @return CheatSheet[]
   */
  public function getSheets (): array {
    return $this->config->sheets;
  }

  /**
   * getPostTypes
   *
   * Returns the sheets property of our Configuration object
   *
   * @return PostType[]
   */
  public function getPostTypes (): array {
    return $this->config->postTypes;
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
}