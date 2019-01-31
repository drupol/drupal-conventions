<?php

namespace drupol\DrupalConventions\PhpCsFixer\Config;

use drupol\DrupalConventions\PhpCsFixer\Fixer\InlineCommentSpacerFixer;
use drupol\DrupalConventions\PhpCsFixer\Fixer\LineLengthFixer;
use drupol\DrupalConventions\PhpCsFixer\Fixer\UppercaseConstantsFixer;
use PhpCsFixer\Config;
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
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getRules() {
    $rules = parent::getRules();

    $filename = __DIR__ . '/../../../' . $this->filename;

    $parsed = (array) Yaml::parseFile($filename) + ['parameters' => []];

    return $parsed['parameters'] + $rules;
  }

  /**
   * {@inheritdoc}
   */
  public function getIndent() {
    return '    ';
  }

  /**
   * {@inheritdoc}
   */
  public function getLineEnding() {
    return "\n";
  }
}
