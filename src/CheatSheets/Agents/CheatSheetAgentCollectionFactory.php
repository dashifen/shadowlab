<?php

namespace Shadowlab\CheatSheets\Agents;

use Dashifen\WPHandler\Agents\Collection\Factory\AgentCollectionFactory;
use Dashifen\WPHandler\Agents\Collection\Factory\AgentCollectionFactoryException;

class CheatSheetAgentCollectionFactory extends AgentCollectionFactory {
  /**
   * registerAgents
   *
   * Given an array of fully namespaced objects, stores them all for later
   * production as a collection.
   *
   * @param array $agents
   *
   * @throws AgentCollectionFactoryException
   */
  public function registerAgents (array $agents): void {
    $agents = array_merge($agents, [
      ACFModificationAgent::class,
      MenuModificationAgent::class,
      TypeRegistrationAgent::class,
    ]);

    $agents = array_unique($agents);
    parent::registerAgents($agents);
  }
}