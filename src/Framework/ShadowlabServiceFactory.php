<?php

namespace Shadowlab\Framework;

use Shadowlab\ShadowlabException;
use Dashifen\Repository\RepositoryException;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Shadowlab\CheatSheets\Services\ACFModifier;
use Shadowlab\CheatSheets\Services\MenuModifier;
use Shadowlab\CheatSheets\Services\TypeRegistration;

/**
 * Class ShadowlabServiceFactory
 *
 * @package Shadowlab\CheatSheets\Services\Factory
 */
class ShadowlabServiceFactory {
  /**
   * @var CheatSheetsPlugin
   */
  protected $handler;

  /**
   * getServices
   *
   * Returns an array of the services that this factory produces.
   *
   * @param CheatSheetsPlugin $handler
   *
   * @return AbstractShadowlabPluginService[]
   * @throws RepositoryException
   * @throws ShadowlabException
   */
  public function getServices (CheatSheetsPlugin $handler): array {
    $this->handler = $handler;

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
    return new ACFModifier($this->handler);
  }

  /**
   * getMenuModifier
   *
   * Returns a MenuModifier service object.
   *
   * @return MenuModifier
   */
  public function getMenuModifier (): MenuModifier {
    return new MenuModifier($this->handler);
  }

  /**
   * getTypeRegistration
   *
   * Returns a TypeRegistration service object.
   *
   * @return TypeRegistration
   */
  public function getTypeRegistration (): TypeRegistration {
    return new TypeRegistration($this->handler);
  }
}