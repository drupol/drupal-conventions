<?php

namespace drupol\DrupalConventions\PhpCsFixer\Config;

/**
 * Class Drupal8.
 */
class Drupal8 extends Drupal
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
