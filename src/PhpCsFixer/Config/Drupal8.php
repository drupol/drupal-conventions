<?php

namespace drupol\DrupalConventions\PhpCsFixer\Config;

use PhpCsFixer\Config;

/**
 * Class Drupal8.
 */
class Drupal8 extends Config
{
  /**
   * @var string
   */
  public $filename = 'config/drupal8/phpcsfixer.rules.yml';

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'drupol/drupal-conventions/drupal8';
  }
}
