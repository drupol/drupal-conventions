<?php

namespace drupol\DrupalConventions\PhpCsFixer\Fixer;

use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

final class InlineCommentSpacerFixer implements DefinedFixerInterface
{
  public function getDefinition()
  {
    return new FixerDefinition(
      'Puts a space after every inline comment start.',
      [
        new CodeSample('<?php //Whut' . \PHP_EOL),
      ]
    );
  }

  public function getPriority()
  {
    return 30;
  }

  public function isCandidate(Tokens $tokens)
  {
    return $tokens->isTokenKindFound(\T_COMMENT);
  }

  /**
   * Check if fixer is risky or not.
   *
   * Risky fixer could change code behavior!
   *
   * @return bool
   */
  public function isRisky() {
    return false;
  }

  /**
   * Fixes a file.
   *
   * @param \SplFileInfo $file A \SplFileInfo instance
   * @param Tokens $tokens Tokens collection
   */
  public function fix(\SplFileInfo $file, Tokens $tokens) {
    foreach ($tokens as $index => $token) {
      $content = $token->getContent();
      if (! $token->isComment() || mb_strpos($content, '//') !== 0 || mb_strpos($content, '// ') === 0) {
        continue;
      }

      $content        = \substr_replace($content, ' ', 2, 0);
      $tokens[$index] = new Token([$token->getId(), $content]);
    }
  }

  /**
   * Returns the name of the fixer.
   *
   * The name must be all lowercase and without any spaces.
   *
   * @return string The name of the fixer
   */
  public function getName() {
    return 'Drupal/inline_comment_spacer';
  }

  /**
   * Returns true if the file is supported by this fixer.
   *
   * @param \SplFileInfo $file
   *
   * @return bool true if the file is supported by this fixer, false otherwise
   */
  public function supports(\SplFileInfo $file) {
    return true;
  }
}
