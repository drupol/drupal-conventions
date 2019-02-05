<?php

namespace drupol\DrupalConventions\PhpCsFixer\Config;

use drupol\DrupalConventions\PhpCsFixer\Fixer\BlankLineBeforeEndOfClass;
use drupol\DrupalConventions\PhpCsFixer\Fixer\InlineCommentSpacerFixer;
use drupol\DrupalConventions\PhpCsFixer\Fixer\LineLengthFixer;
use drupol\DrupalConventions\PhpCsFixer\Fixer\UppercaseConstantsFixer;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Drupal.
 */
abstract class Drupal extends Config
{

  /**
   * {@inheritdoc}
   */
  public function getCustomFixers() {
    return [
      new UppercaseConstantsFixer(),
      new InlineCommentSpacerFixer(),
      new LineLengthFixer($this->getIndent(), $this->getLineEnding()),
      new BlankLineBeforeEndOfClass($this->getIndent(), $this->getLineEnding()),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getRules() {
    $filename = __DIR__ . '/../../../' . $this->filename;

    $parsed = (array) Yaml::parseFile($filename) + ['parameters' => []];

    return $parsed['parameters'];
  }

  /**
   * {@inheritdoc}
   */
  public function getIndent() {
    return '  ';
  }

  /**
   * {@inheritdoc}
   */
  public function getLineEnding() {
    return "\n";
  }

  /**
   * {@inheritdoc}
   */
  public function getFinder() {
    return Finder::create()
      ->files()
      ->name('*.inc')
      ->name('*.install')
      ->name('*.module')
      ->name('*.profile')
      ->name('*.php')
      ->name('*.theme')
      ->ignoreDotFiles(true)
      ->ignoreVCS(true)
      ->exclude(['build', 'libraries', 'node_modules', 'vendor'])
      ->in($_SERVER['PWD']);
  }
}
