<?php

namespace Shadowlab\CheatSheets\Agents\CollectionFactory;

use Shadowlab\CheatSheets\Agents\ACFModificationAgent;
use Shadowlab\CheatSheets\Agents\MenuModificationAgent;
use Shadowlab\CheatSheets\Agents\TypeRegistrationAgent;
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

    // we don't expect anyone to send us anything in the $agents array since
    // our DI container within the Shadowlab object will handle the
    // construction of our collection factory.  but, just in case someone
    // does, we'll make sure we have only unique classes list before we
    // register them all.

    $agents = array_unique($agents);
    parent::registerAgents($agents);
  }
}