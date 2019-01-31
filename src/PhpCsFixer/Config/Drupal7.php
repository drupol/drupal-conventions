<?php

namespace drupol\DrupalConventions\PhpCsFixer\Config;

use PhpCsFixer\Config;

/**
 * Class Drupal7.
 */
class Drupal7 extends Config
{
  /**
   * @var string
   */
  public $filename = 'config/drupal7/phpcsfixer.rules.yml';

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'drupol/drupal-conventions/drupal7';
  }
}
