<?php

namespace Shadowlab\Traits;

use Shadowlab\Framework\Controller;
use Shadowlab\Repositories\CheatSheets\PostType;
use Shadowlab\Repositories\CheatSheets\CheatSheet;
use Shadowlab\Repositories\Framework\Configuration;

/**
 * Trait ConfigurationTrait
 *
 * This trait is applied to objects that have a Controller property so that
 * we can add convenience methods which access our app's Configuration.
 *
 * @property-read Controller $controller
 * @package Shadowlab\Traits
 */
trait ConfigurationTrait {
  /**
   * getConfiguration
   *
   * Returns the read-only Configuration object from within our Controller.
   *
   * @return Configuration
   */
  public function getConfiguration(): Configuration {
    return $this->controller->configuration;
  }

  /**
   * getCheatSheets
   *
   * Returns the read-only sheets property of the Configuration object within
   * our Controller.
   *
   * @return CheatSheet[]
   */
  public function getCheatSheets(): array {
    return $this->controller->configuration->sheets;
  }

  /**
   * getPostTypes
   *
   * Returns the read-only postTypes property of the Configuration object
   * within our Controller.
   *
   * @return PostType[]
   */
  public function getPostTypes(): array {
    return $this->controller->configuration->postTypes;
  }

  /**
   * getSheet
   *
   * Calls and returns the result of the Configuration::getSheet() method
   * using the Configuration object that's within our Controller.
   *
   * @param string $sheetTitle
   *
   * @return CheatSheet|null
   */
  public function getSheet(string $sheetTitle): ?CheatSheet {
    return $this->controller->configuration->getSheet($sheetTitle);
  }

  /**
   * getSheetId
   *
   * Calls and returns the result of the Configuration::getSheetId() method
   * using the Configuration object that's within our Controller.
   *
   * @param string $sheetTitle
   *
   * @return int
   */
  public function getSheetId(string $sheetTitle): int {
    return $this->controller->configuration->getSheetId($sheetTitle);
  }

  /**
   * getPostType
   *
   * Calls and returns the result of the Configuration::getPostType() method
   * using the Configuration object that's within our Controller.
   *
   * @param string $postType
   *
   * @return PostType|null
   */
  public function getPostType(string $postType): ?PostType {
    $postTypeObject = $this->controller->configuration->getPostType($postType);

    if (!is_null($postTypeObject)) {
      return $postTypeObject;
    }

    // if we couldn't find the object using our parameter, we'll try to do
    // it the long way.  we're not sure why the parameter started becoming
    // "post" recently, but until we figure that out, we'll grab the plural
    // version of the post type off of the request URI and then we can use
    // that to search.

    $urlParts = array_filter(explode("/", $_SERVER["REQUEST_URI"]));
    $pluralPostType = array_pop($urlParts);

    foreach ($this->getPostTypes() as $potentialPostType) {
      if ($pluralPostType === strtolower($potentialPostType->plural)) {
        return $potentialPostType;
      }
    }

    // if we still couldn't find it, we'll return null and hope the calling
    // scope knows what to do.

    return null;
  }
}