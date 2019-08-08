<?php

namespace Shadowlab\CheatSheets\Services\Factory;

use MongoDB\BSON\Type;
use Shadowlab\ShadowlabException;
use Dashifen\Repository\RepositoryException;
use Shadowlab\CheatSheets\Services\ACFModifier;
use Shadowlab\CheatSheets\Services\MenuModifier;
use Shadowlab\CheatSheets\Services\TypeRegistration;
use Shadowlab\CheatSheets\Services\AbstractShadowlabPluginService;

/**
 * Class ShadowlabServiceFactory
 *
 * @package Shadowlab\CheatSheets\Services\Factory
 */
class ShadowlabServiceFactory {
  /**
   * getServices
   *
   * Returns an array of the services that this factory produces.
   *
   * @return AbstractShadowlabPluginService[]
   * @throws RepositoryException
   * @throws ShadowlabException
   */
  public function getServices(): array {
    return [
      $this->getACFModifier(),
      $this->getMenuModifier(),
      $this->getTypeRegistration(),
    ];
  }

  /**
   * getACFModifier
   *
   * Returns an ACFModifier service object.
   *
   * @return ACFModifier
   * @throws RepositoryException
   * @throws ShadowlabException
   */
  public function getACFModifier (): ACFModifier {
    return new ACFModifier();
  }

  /**
   * getMenuModifier
   *
   * Returns a MenuModifier service object.
   *
   * @return MenuModifier
   */
  public function getMenuModifier (): MenuModifier {
    return new MenuModifier();
  }

  /**
   * getTypeRegistration
   *
   * Returns a TypeRegistration service object.
   *
   * @return TypeRegistration
   */
  public function getTypeRegistration (): TypeRegistration {
    return new TypeRegistration();
  }
}