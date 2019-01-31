<?php

namespace drupol\DrupalConventions\PhpCsFixer\Fixer;

use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

/**
 * Class InlineCommentSpacerFixer.
 */
final class InlineCommentSpacerFixer implements DefinedFixerInterface
{
  /**
   * {@inheritdoc}
   */
  public function fix(\SplFileInfo $file, Tokens $tokens) {
    foreach ($tokens as $index => $token) {
      $content = $token->getContent();
      if (! $token->isComment() || mb_strpos($content, '//') !== 0 || mb_strpos($content, '// ') === 0) {
        continue;
      }

      $content = \substr_replace($content, ' ', 2, 0);
      $tokens[$index] = new Token([$token->getId(), $content]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition()
  {
    return new FixerDefinition(
      'Puts a space after every inline comment start.',
      [
        new CodeSample('<?php //Whut' . \PHP_EOL),
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'Drupal/inline_comment_spacer';
  }

  /**
   * {@inheritdoc}
   */
  public function getPriority()
  {
    return 30;
  }

  /**
   * {@inheritdoc}
   */
  public function isCandidate(Tokens $tokens)
  {
    return $tokens->isTokenKindFound(\T_COMMENT);
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
  public function supports(\SplFileInfo $file) {
    return true;
  }
}
