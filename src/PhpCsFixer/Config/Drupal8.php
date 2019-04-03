<?php

namespace drupol\DrupalConventions\PhpCsFixer\Config;

/**
 * Class Drupal8.
 */
final class Drupal8 extends Drupal
{
  /**
   * @var string
   */
  public static $rules = '/../../../config/drupal8/phpcsfixer.rules.yml';

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'drupol/drupal-conventions/drupal8';
  }

  /**
   * {@inheritdoc}
   */
  public function getRiskyAllowed() {
    return true;
  }
}
