<?php

namespace drupol\DrupalConventions\PhpCsFixer\Fixer;

use PhpCsFixer\Fixer\DefinedFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\Preg;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;

/**
 * Class ControlStructureCurlyBracketsElseFixer.
 */
class ControlStructureCurlyBracketsElseFixer implements DefinedFixerInterface, WhitespacesAwareFixerInterface
{
  /**
   * @var \PhpCsFixer\WhitespacesFixerConfig
   */
  private $whitespacesConfig;


  /**
   * ControlStructureCurlyBracketsElseFixer constructor.
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
  public function fix(\SplFileInfo $file, Tokens $tokens)
  {
    foreach ($tokens as $index => $token) {
      if (!$token->isGivenKind([T_ELSE, T_ELSEIF])) {
        continue;
      }

      $tokens[$index-1] = new Token([
        T_WHITESPACE,
        $this->whitespacesConfig->getLineEnding()]
      );

      $padding = substr(
        $this->getExpectedIndentAt($tokens, $index),
        0,
        - strlen($this->whitespacesConfig->getIndent())
      );

      $tokens[$index] = new Token([
        T_WHITESPACE,
        $padding . $tokens[$index]->getContent()]
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getPriority()
  {
    return -1000;
  }

  /**
   * {@inheritdoc}
   */
  public function supports(\SplFileInfo $file) {
    return true;
  }

  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return 'Drupal/control_structure_braces_else';
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition()
  {
    return new FixerDefinition(
      'Fix if/else control structure.',
      [
        new CodeSample(
          ''
        ),
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function isCandidate(Tokens $tokens) {
    return $tokens->isAnyTokenKindsFound([T_IF, T_ELSE, T_ELSEIF]);
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
  public function setWhitespacesConfig(WhitespacesFixerConfig $config) {
    $this->whitespacesConfig = $config;
  }

  /**
   * Mostly taken from MethodChainingIndentationFixer.
   *
   * @param Tokens $tokens
   * @param int    $index  index of the first token on the line to indent
   *
   * @return string
   */
  private function getExpectedIndentAt(Tokens $tokens, $index)
  {
    $index = $tokens->getPrevMeaningfulToken($index);
    $indent = $this->whitespacesConfig->getIndent();

    for ($i = $index; $i >= 0; --$i) {
      if ($tokens[$i]->equals(')')) {
        $i = $tokens->findBlockStart(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $i);
      }

      $currentIndent = $this->getIndentAt($tokens, $i);
      if (null === $currentIndent) {
        continue;
      }

      if ($this->currentLineRequiresExtraIndentLevel($tokens, $i, $index)) {
        return $currentIndent.$indent;
      }

      return $currentIndent;
    }

    return $indent;
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
   * Mostly taken from MethodChainingIndentationFixer.
   *
   * @param Tokens $tokens
   * @param int    $start  index of first meaningful token on previous line
   * @param int    $end    index of last token on previous line
   *
   * @return bool
   */
  private function currentLineRequiresExtraIndentLevel(Tokens $tokens, $start, $end)
  {
    if ($tokens[$start + 1]->isGivenKind(T_OBJECT_OPERATOR)) {
      return false;
    }

    if ($tokens[$end]->isGivenKind(CT::T_BRACE_CLASS_INSTANTIATION_CLOSE)) {
      return true;
    }

    return
      !$tokens[$end]->equals(')')
      || $tokens->findBlockStart(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $end) >= $start
      ;
  }

}
