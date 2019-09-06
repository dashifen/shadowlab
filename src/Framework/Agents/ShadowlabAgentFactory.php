<?php

namespace Shadowlab\Framework\Agents;

use Shadowlab\Framework\Exception;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Dashifen\Repository\RepositoryException;
use Shadowlab\CheatSheets\Agents\ACFModifier;
use Shadowlab\CheatSheets\Agents\MenuModifier;
use Shadowlab\CheatSheets\Agents\TypeRegistration;

/**
 * Class ShadowlabServiceFactory
 *
 * @package Shadowlab\CheatSheets\Services\Factory
 */
class ShadowlabAgentFactory {
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
   * @return AbstractShadowlabPluginAgent[]
   * @throws RepositoryException
   * @throws Exception
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
   * @throws Exception
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