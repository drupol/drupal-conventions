<?php

namespace drupol\DrupalConventions\PhpCsFixer\Config;

/**
 * Class Drupal7.
 */
class Drupal7 extends Drupal
{
  /**
   * @var string
   */
  public static $rules = '/../../../config/drupal7/phpcsfixer.rules.yml';

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'drupol/drupal-conventions/drupal7';
  }
}
