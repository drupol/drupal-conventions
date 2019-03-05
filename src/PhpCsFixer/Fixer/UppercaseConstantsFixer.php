<?php

namespace drupol\DrupalConventions\PhpCsFixer\Fixer;

use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * Class UppercaseConstantsFixer.
 */
final class UppercaseConstantsFixer implements DefinedFixerInterface
{
  /**
   * {@inheritdoc}
   */
  public function fix(\SplFileInfo $file, Tokens $tokens) {
    foreach ($tokens as $index => $token) {
      if (!$token->isNativeConstant()) {
        continue;
      }

      if ($this->isNeighbourAccepted($tokens, $tokens->getPrevMeaningfulToken($index)) &&
        $this->isNeighbourAccepted($tokens, $tokens->getNextMeaningfulToken($index))
      ) {
        $tokens[$index] = new Token([$token->getId(), strtoupper($token->getContent())]);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition() {
    return new FixerDefinition(
      'The PHP constants `true`, `false`, and `null` MUST be in upper case.',
      [new CodeSample("<?php\n\$a = FALSE;\n\$b = True;\n\$c = nuLL;\n")]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'Drupal/uppercase_constants';
  }

  /**
   * {@inheritdoc}
   */
  public function getPriority() {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function isCandidate(Tokens $tokens) {
    return $tokens->isTokenKindFound(T_STRING);
  }

  /**
   * {@inheritdoc}
   */
  public function isRisky() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function supports(\SplFileInfo $file) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  private function isNeighbourAccepted(Tokens $tokens, $index) {
    static $forbiddenTokens = [
      T_AS,
      T_CLASS,
      T_CONST,
      T_EXTENDS,
      T_IMPLEMENTS,
      T_INSTANCEOF,
      T_INSTEADOF,
      T_INTERFACE,
      T_NEW,
      T_NS_SEPARATOR,
      T_OBJECT_OPERATOR,
      T_PAAMAYIM_NEKUDOTAYIM,
      T_TRAIT,
      T_USE,
      CT::T_USE_TRAIT,
      CT::T_USE_LAMBDA,
    ];

    $token = $tokens[$index];

    if ($token->equalsAny(['{', '}'])) {
      return FALSE;
    }

    return !$token->isGivenKind($forbiddenTokens);
  }
}
