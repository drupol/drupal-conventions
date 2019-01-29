<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace drupol\DrupalConventions\PhpCsFixer\Fixer;

use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Preg;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;

/**
 * Class DrupalIndentationFixer
 */
class DrupalTabToSpaceFixer implements DefinedFixerInterface, WhitespacesAwareFixerInterface
{
  /**
   * @var WhitespacesFixerConfig
   */
  private $whitespaceconfig;

  /**
   * {@inheritdoc}
   */
  public function fix(\SplFileInfo $file, Tokens $tokens)
  {
    foreach ($tokens as $index => $token) {
      if ($token->isComment()) {
        $tokens[$index] = $this->fixIndentInComment($tokens, $index);

        continue;
      }

      if ($token->isWhitespace()) {
        $tokens[$index] = $this->fixIndentToken($tokens, $index);

        continue;
      }
    }
  }

  public function getPriority()
  {
    return 50;
  }

  public function supports(\SplFileInfo $file)
  {
    return true;
  }

  public function getName()
  {
    return 'Drupal/tab_to_space';
  }

  /**
   * Returns the definition of the fixer.
   *
   * @return FixerDefinitionInterface
   */
  public function getDefinition() {
    return new FixerDefinition(
      'Transform tabs in spaces.',
      [new CodeSample("<?php\t\$a = 'One tab';\t\t\$b = 'Two tabs';\n")]
    );
  }

  /**
   * Check if the fixer is a candidate for given Tokens collection.
   *
   * Fixer is a candidate when the collection contains tokens that may be fixed
   * during fixer work. This could be considered as some kind of bloom filter.
   * When this method returns true then to the Tokens collection may or may not
   * need a fixing, but when this method returns false then the Tokens collection
   * need no fixing for sure.
   *
   * @param Tokens $tokens
   *
   * @return bool
   */
  public function isCandidate(Tokens $tokens) {
    return true;
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
   * @param \PhpCsFixer\WhitespacesFixerConfig $config
   */
  public function setWhitespacesConfig(WhitespacesFixerConfig $config) {
    static $defaultWhitespacesFixerConfig = null;

    if (null === $defaultWhitespacesFixerConfig) {
      $defaultWhitespacesFixerConfig = new WhitespacesFixerConfig('    ', "\n");
    }

    $this->whitespaceconfig = $defaultWhitespacesFixerConfig;
  }

  /**
   * @param Tokens $tokens
   * @param int    $index
   *
   * @return Token
   */
  private function fixIndentInComment(Tokens $tokens, $index)
  {
    $content = Preg::replace('/^(?:(?<! ) {1,3})?\t/m', '\1    ', $tokens[$index]->getContent(), -1, $count);
    $indent = $this->whitespaceconfig->getIndent();

    // Also check for more tabs.
    while (0 !== $count) {
      $content = Preg::replace('/^(\ +)?\t/m', '\1    ', $content, -1, $count);
    }

    // change indent to expected one
    $content = Preg::replaceCallback('/^(?:    )+/m', static function ($matches) use ($indent) {
      return str_replace('    ', $indent, $matches[0]);
    }, $content);

    return new Token([$tokens[$index]->getId(), $content]);
  }

  /**
   * @param Tokens $tokens
   * @param int    $index
   *
   * @return Token
   */
  private function fixIndentToken(Tokens $tokens, $index)
  {
    $content = $tokens[$index]->getContent();
    $previousTokenHasTrailingLinebreak = false;
    $indent = $this->whitespaceconfig->getIndent();

    // @TODO 3.0 this can be removed when we have a transformer for "T_OPEN_TAG" to "T_OPEN_TAG + T_WHITESPACE"
    if (false !== strpos($tokens[$index - 1]->getContent(), "\n")) {
      $content = "\n".$content;
      $previousTokenHasTrailingLinebreak = true;
    }

    $newContent = Preg::replaceCallback(
      '/(\R)(\h+)/', // find indent
      static function (array $matches) use ($indent) {
        // normalize mixed indent
        $content = Preg::replace('/(?:(?<! ) {1,3})?\t/', '  ', $matches[2]);

        // change indent to expected one
        return $matches[1].str_replace('    ', $indent, $content);
      },
      $content
    );

    if ($previousTokenHasTrailingLinebreak) {
      $newContent = substr($newContent, 1);
    }

    return new Token([T_WHITESPACE, $newContent]);
  }
}
