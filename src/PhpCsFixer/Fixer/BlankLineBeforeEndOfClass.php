<?php

namespace drupol\DrupalConventions\PhpCsFixer\Fixer;

use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\TokensAnalyzer;
use PhpCsFixer\WhitespacesFixerConfig;

final class BlankLineBeforeEndOfClass implements DefinedFixerInterface, WhitespacesAwareFixerInterface
{
  /**
   * @var Tokens
   */
  private $tokens;

  /**
   * @var TokensAnalyzer
   */
  private $tokensAnalyzer;

  /**
   * @var \PhpCsFixer\WhitespacesFixerConfig
   */
  private $whitespacesConfig;

  /**
   * BlankLineAfterStatementFixer constructor.
   */
  public function __construct($indent, $lineEnding) {
    $this->setWhitespacesConfig(
      new WhitespacesFixerConfig($indent, $lineEnding)
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getPriority()
  {
    return -10000;
  }

  /**
   * {@inheritdoc}
   */
  public function isCandidate(Tokens $tokens)
  {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function fix(\SplFileInfo $file, Tokens $tokens)
  {
    $this->tokens = $tokens;
    $this->tokensAnalyzer = new TokensAnalyzer($this->tokens);

    foreach ($tokens as $index => $token) {
      if (!$token->isClassy()) {
        continue;
      }

      $indexOpenCurlyBrace = $tokens->getNextTokenOfKind($index, ['{']);

      $endCurlyBraceIndex = $tokens->findBlockEnd(
        Tokens::BLOCK_TYPE_CURLY_BRACE,
        $indexOpenCurlyBrace
      );

      $this->tokens[$endCurlyBraceIndex] = new Token([
        T_WHITESPACE,
        $this->whitespacesConfig->getLineEnding() . $this->tokens[$endCurlyBraceIndex]->getContent()
      ]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function isRisky() {
    return false;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'Drupal/blank_line_before_end_of_class';
  }

  /**
   * {@inheritdoc}
   */
  public function supports(\SplFileInfo $file) {
    return true;
  }

  public function setWhitespacesConfig(WhitespacesFixerConfig $config) {
    $this->whitespacesConfig = $config;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition()
  {
    return new FixerDefinition(
      'A class must have a blank line before the last closing brace.',
      [
        new CodeSample(
          ''
        ),
      ]
    );
  }
}
