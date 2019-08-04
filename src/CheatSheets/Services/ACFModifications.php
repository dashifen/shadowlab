<?php

namespace Shadowlab\CheatSheets\Services;

use DirectoryIterator;
use Shadowlab\ShadowlabException;
use Shadowlab\Repositories\ACFDefinition;
use Dashifen\WPHandler\Hooks\HookException;
use Dashifen\Repository\RepositoryException;
use Shadowlab\CheatSheets\CheatSheetsPlugin;
use Dashifen\WPHandler\Services\AbstractPluginService;

class ACFModifications extends AbstractPluginService {
  /**
   * @var CheatSheetsPlugin
   */
  protected $handler;

  /**
   * @var string
   */
  private $fieldGroupFolder = "";

  /**
   * AbstractPluginService constructor.
   *
   * @param CheatSheetsPlugin $handler
   *
   * @throws ShadowlabException
   */
  public function __construct (CheatSheetsPlugin $handler) {
    $this->fieldGroupFolder = sprintf("%s/field-groups", $handler->getPluginDir());
    $handler->getController()->setAcfFolder($this->fieldGroupFolder);
    parent::__construct($handler);
  }

  /**
   * initialize
   *
   * Uses addAction() and addFilter() to connect WordPress to the methods
   * of this object's child which are intended to be protected.
   *
   * @return void
   * @throws HookException
   */
  public function initialize (): void {
    if (!$this->isInitialized()) {
      $this->addFilter('acf/settings/save_json', 'getAcfFieldGroupFolder');
    }
  }

  protected function getAcfFieldGroupFolder (): string {
    return $this->fieldGroupFolder;
  }
}