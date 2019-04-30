<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace drupol\DrupalConventions\PhpCsFixer\Fixer;

use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Preg;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\TokensAnalyzer;
use PhpCsFixer\WhitespacesFixerConfig;

final class NewlineAfterLastCommaInArrayFixer implements DefinedFixerInterface,WhitespacesAwareFixerInterface
{
  /**
   * @var \PhpCsFixer\WhitespacesFixerConfig
   */
  private $whitespacesConfig;

  /**
   * NewlineAfterLastCommaInArrayFixer constructor.
   *
   * @param $indent
   * @param $lineEnding
   */
  public function __construct($indent, $lineEnding) {
    $this->setWhitespacesConfig(
      new WhitespacesFixerConfig($indent, $lineEnding)
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setWhitespacesConfig(WhitespacesFixerConfig $config) {
    $this->whitespacesConfig = $config;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition()
  {
    return new FixerDefinition(
      'In array declaration, if the array is multiline, the closing tag must be on a newline.',
      [new CodeSample("<?php\n\$sample = array(1,'a',\$b,);\n")]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isCandidate(Tokens $tokens)
  {
    return $tokens->isAnyTokenKindsFound([T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN]);
  }

  /**
   * {@inheritdoc}
   */
  public function fix(\SplFileInfo $file, Tokens $tokens)
  {
    $tokensAnalyzer = new TokensAnalyzer($tokens);

    for ($index = $tokens->count() - 1; $index >= 0; --$index) {
      if ($tokensAnalyzer->isArray($index) && $tokensAnalyzer->isArrayMultiLine($index)) {
        $this->fixArray($tokens, $index);
      }
    }
  }

  /**
   * @param Tokens $tokens
   * @param int    $index
   */
  private function fixArray(Tokens $tokens, $index)
  {
    $startIndex = $index;

    if ($tokens[$startIndex]->isGivenKind(T_ARRAY)) {
      $startIndex = $tokens->getNextTokenOfKind($startIndex, ['(']);
      $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $startIndex);
    } else {
      $endIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_ARRAY_SQUARE_BRACE, $startIndex);
    }

    $equalIndex = $tokens->getPrevTokenOfKind($startIndex - 1, ['=']);

    $indent = '';
    if ($equalIndex !== NULL) {
      $assignedVarIndex = $tokens->getPrevMeaningfulToken($equalIndex);
      $indent = $this->getIndentAt($tokens, $assignedVarIndex-1);
    }

    $lineEnding = $this->whitespacesConfig->getLineEnding();

    $beforeEndIndex = $tokens->getPrevMeaningfulToken($endIndex);
    $beforeEndToken = $tokens[$beforeEndIndex];

    if ($startIndex !== $beforeEndIndex && !$beforeEndToken->equalsAny([$lineEnding])) {
        $tokens->insertAt($beforeEndIndex + 1, new Token([T_WHITESPACE, $lineEnding]));
    }
  }

  /**
   * Mostly taken from MethodChainingIndentationFixer.
   *
   * @param Tokens $tokens
   * @param int    $index  index of the indentation token
   *
   * @return null|string
   */
  private function getIndentAt(Tokens $tokens, $index)
  {
    if (1 === Preg::match('/\R{1}([ \t]*)$/', $this->getIndentContentAt($tokens, $index), $matches)) {
      return $matches[1];
    }

    return null;
  }

  /**
   * Mostly taken from MethodChainingIndentationFixer.
   *
   * {@inheritdoc}
   */
  private function getIndentContentAt(Tokens $tokens, $index)
  {
    for ($i = $index; $i >= 0; --$i) {
      if (!$tokens[$index]->isGivenKind([T_WHITESPACE, T_INLINE_HTML])) {
        continue;
      }

      $content = $tokens[$index]->getContent();

      if ($tokens[$index]->isWhitespace() && $tokens[$index - 1]->isGivenKind(T_OPEN_TAG)) {
        $content = $tokens[$index - 1]->getContent().$content;
      }

      if (Preg::match('/\R/', $content)) {
        return $content;
      }
    }

    return '';
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
   * Returns the name of the fixer.
   *
   * The name must be all lowercase and without any spaces.
   *
   * @return string The name of the fixer
   */
  public function getName() {
    return 'Drupal/new_line_on_multiline_array';
  }

  /**
   * Returns the priority of the fixer.
   *
   * The default priority is 0 and higher priorities are executed first.
   *
   * @return int
   */
  public function getPriority() {
    return 10000;
  }

  /**
   * Returns true if the file is supported by this fixer.
   *
   * @param \SplFileInfo $file
   *
   * @return bool true if the file is supported by this fixer, false otherwise
   */
  public function supports(\SplFileInfo $file) {
    return TRUE;
  }
}
