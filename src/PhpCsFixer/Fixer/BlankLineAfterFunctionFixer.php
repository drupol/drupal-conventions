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

final class BlankLineAfterFunctionFixer implements DefinedFixerInterface, WhitespacesAwareFixerInterface
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
  public function __construct() {
    $config = new WhitespacesFixerConfig('    ', "\n");

    $this->setWhitespacesConfig($config);
  }

  /**
   * {@inheritdoc}
   */
  public function getPriority()
  {
    return 10000;
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
    for ($index = $tokens->getSize() - 1; $index > 0; --$index) {
      $this->fixByToken($tokens[$index], $index);
    }
  }

  /**
   * @param \PhpCsFixer\Tokenizer\Token $token
   * @param $index
   */
  private function fixByToken(Token $token, $index)
  {
    if (!$token->equals('{')) {
      return;
    }

    for ($i = $index - 1; $i > 0; --$i) {
      if ($this->tokens[$i]->isGivenKind(T_FUNCTION) && $this->tokensAnalyzer->isLambda($i)) {
        return;
      }

      if ($this->tokens[$i]->isGivenKind(T_CLASS) && $this->tokensAnalyzer->isAnonymousClass($i)) {
        return;
      }

      if ($this->tokens[$i]->isWhitespace() && false !== strpos($this->tokens[$i]->getContent(), "\n")) {
        break;
      }
    }

    $lineEnding = $this->whitespacesConfig->getLineEnding();

    $tokenCount = \count($this->tokens);

    for ($end = $index; $end < $tokenCount; ++$end) {
      if ($this->tokens[$end]->equals('}')) {
        if (isset($this->tokens[$end+1])) {

          $content = $this->tokens[$end+1]->getContent();

          // If the function is in a variable or used as a callback, continue.
          if ($content === ';' || $content === ')') {
            continue;
          }

          $this->tokens[$end+1] = new Token([
            T_WHITESPACE,
            $lineEnding . $this->tokens[$end+1]->getContent()
          ]);
        }
      }
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
    return 'Drupal/blank_line_after_function';
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
      'A function must have a blank line after the end of it.',
      [
        new CodeSample(
          '<?php
function A() {
}
function B() {
}
'
        ),
      ]
    );
  }
}
