<?php

namespace drupol\DrupalConventions\PhpCsFixer\Config;

use drupol\DrupalConventions\PhpCsFixer\Fixer\BlankLineBeforeEndOfClass;
use drupol\DrupalConventions\PhpCsFixer\Fixer\ControlStructureCurlyBracketsElseFixer;
use drupol\DrupalConventions\PhpCsFixer\Fixer\InlineCommentSpacerFixer;
use drupol\DrupalConventions\PhpCsFixer\Fixer\LineLengthFixer;
use drupol\DrupalConventions\PhpCsFixer\Fixer\UppercaseConstantsFixer;
use PhpCsFixer\Config as PhpCsFixerConfig;
use PhpCsFixer\Finder;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Drupal.
 */
abstract class Drupal extends PhpCsFixerConfig implements Config
{
  /**
   * @var string
   */
  public static $rules = '/../../../config/drupal/phpcsfixer.rules.yml';

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

  /**
   * {@inheritdoc}
   */
  public function alterCustomFixers(array &$fixers): void {

  }

  /**
   * {@inheritdoc}
   */
  public function alterRules(array &$rules): void {

  }

  /**
   * {@inheritdoc}
   */
  final public function getCustomFixers(): array
  {
    $fixers = parent::getCustomFixers();

    $fixers = array_merge($fixers, [
      new BlankLineBeforeEndOfClass($this->getIndent(), $this->getLineEnding()),
      new ControlStructureCurlyBracketsElseFixer($this->getIndent(), $this->getLineEnding()),
      new InlineCommentSpacerFixer(),
      new LineLengthFixer($this->getIndent(), $this->getLineEnding()),
      new UppercaseConstantsFixer(),
    ]);

    // @todo: is this really required.
    $this->alterCustomFixers($fixers);

    return $fixers;
  }

  /**
   * {@inheritdoc}
   */
  final public function getRules() {
    $rules = parent::getRules();

    $classes = class_parents(static::class);
    array_unshift($classes, static::class);

    foreach (array_reverse(array_values($classes)) as $class) {
      if (!isset($class::$rules)) {
        continue;
      }

      $filename = __DIR__ . $class::$rules;

      if (!file_exists($filename)) {
        continue;
      }

      $parsed = (array) Yaml::parseFile($filename);
      $parsed['parameters'] = (array) $parsed['parameters'] + ['rules' => []];
      $rules = array_merge($rules, $parsed['parameters']['rules']);
    }

    // @todo: is this really required.
    $this->alterRules($rules);

    ksort($rules);

    return $rules;
  }
}
