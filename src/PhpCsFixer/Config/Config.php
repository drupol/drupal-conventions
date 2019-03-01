<?php

declare(strict_types = 1);

namespace drupol\DrupalConventions\PhpCsFixer\Config;

use PhpCsFixer\ConfigInterface;

/**
 * Interface Config.
 */
interface Config extends ConfigInterface
{
  /**
   * This hook let you alter the fixers programmatically.
   *
   * @param array $fixers
   *   The custom fixers.
   */
  public function alterCustomFixers(array &$fixers);
  /**
   * This hook let you alter the rules programmatically.
   *
   * @param array $rules
   *   The rules.
   */
  public function alterRules(array &$rules);
}
