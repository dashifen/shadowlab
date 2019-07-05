<?php

namespace Shadowlab\Framework\ActionFinder;

use Exception;
use HaydenPierce\ClassFinder\ClassFinder;

class ActionFinder {
  /**
   * getActions
   *
   * Returns classes whose names end with "Action."
   *
   * @return array
   * @throws Exception
   */
  public function getActions(): array {
    try {

      // we can use the ClassFinder object to find all the objects within the
      // specified namespace.  that object throws an \Exception, which we'll
      // catch and then re-throw as one of our exceptions so that it can be
      // more easily caught in the calling scope.

      $classes = ClassFinder::getClassesInNamespace("Shadowlab", ClassFinder::RECURSIVE_MODE);
    } catch (Exception $e) {
      throw new ActionFinderException("Unable to find classes.",
        ActionFinderException::UNKNOWN_ERROR,
        $e);
    }

    // $classes is the full list of all classes in our Shadowlab namespace.
    // now, we want to filter for only those that are Actions.

    return array_filter($classes, function (string $className): bool {
      return preg_match("/Action$/", $className);
    });
  }
}