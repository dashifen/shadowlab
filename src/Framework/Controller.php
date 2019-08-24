<?php

namespace Shadowlab\Framework;

use DirectoryIterator;
use Symfony\Component\Yaml\Yaml;
use Dashifen\Repository\Repository;
use Dashifen\Repository\RepositoryException;
use Shadowlab\Repositories\Framework\ACFDefinition;
use Shadowlab\Repositories\Framework\Configuration;
use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Class Controller
 *
 * @package Shadowlab
 * @property-read Configuration   $configuration
 * @property-read string          $acfFolder
 * @property-read ACFDefinition[] $acfDefinitions
 */
class Controller extends Repository {
  /**
   * @var Configuration
   */
  protected $configuration;

  /**
   * @var string
   */
  protected $acfFolder = "";

  /**
   * @var array
   */
  protected $acfDefinitions = [];

  /**
   * Controller constructor.
   *
   * @throws Exception
   * @throws RepositoryException
   */
  public function __construct () {
    parent::__construct();
    $this->setConfiguration();
  }

  /**
   * setConfig
   *
   * Parses the config.yaml file and sets our static config property.
   *
   * @return void
   * @throws RepositoryException
   * @throws Exception
   */
  protected function setConfiguration (): void {
    $configFile = ABSPATH . "../config.yaml";

    if (!is_file($configFile)) {
      throw new Exception("Unable to find config.yaml",
        Exception::CONFIG_FILE_NOT_FOUND);
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
      $this->configuration = new Configuration([
        "sheets"    => $yaml["sheets"],
        "postTypes" => $yaml["sheets"],
      ]);
    } catch (ParseException $e) {

      // rather than throw symfony's exception, we'll throw ours.  so, here
      // we convert the former to the latter.

      throw new Exception("Unable to parse config.yaml",
        Exception::CONFIG_FILE_INVALID);
    }
  }

  /**
   * setAcfFolder
   *
   * Sets the ACF Folder property.
   *
   * @param string $acfFolder
   *
   * @return void
   * @throws Exception
   * @throws RepositoryException
   */
  public function setAcfFolder (string $acfFolder): void {
    if (!is_dir($acfFolder)) {
      throw new Exception("$acfFolder is not a directory.",
        Exception::INVALID_ACF_FOLDER);
    }

    $this->acfFolder = $acfFolder;
    $this->setAcfDefinitions();
  }

  /**
   * setAcfDefinitions
   *
   * Called after our folder is set, this initializes a map of field group
   * names to files that we use when identifying cheat sheets.
   *
   * @return void
   * @throws RepositoryException
   */
  protected function setAcfDefinitions (): void {
    $files = new DirectoryIterator($this->acfFolder);

    foreach ($files as $file) {
      if ($file->getExtension() === "json") {
        $path = $file->getPathname();
        $contents = file_get_contents($path);
        $title = json_decode($contents)->title;
        $this->acfDefinitions[$title] = new ACFDefinition([
          "title"        => $title,
          "lastModified" => filemtime($path),
          "file"         => $path,
        ]);
      }
    }
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
   * toStudlyCaps
   *
   * Given a wimpy string return it in StudlyCaps.
   *
   * @param string $wimpyString
   *
   * @return string
   */
  public static function toStudlyCaps (string $wimpyString): string {

    // splitting our string based on non-word characters give us the set of
    // words that within our wimpy string.  then, we can walk the array and
    // use ucfirst to capitalize each word.  finally, we join it all together
    // and return our studly string.

    return join("", array_map("ucfirst", preg_split("/\W+/", $wimpyString)));
  }

  /**
   * toKebabCase
   *
   * Takes a StudlyCaps string and returns it in kebab-case.
   *
   * @param string $studlyString
   *
   * @return string
   */
  public static function toKebabCase (string $studlyString): string {

    // we add a dash between lower-to-capital patterns using preg_replace()
    // and then lower case the resulting string to take StudlyCaps and return
    // kebab-case.

    return strtolower(preg_replace("/([a-z])([A-Z])/", '%1-%2', $studlyString));
  }
}