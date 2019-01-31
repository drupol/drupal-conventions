<?php

namespace drupol\DrupalConventions\PhpCsFixer\Fixer;

use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

/**
 * Class LineLengthFixer.
 */
final class LineLengthFixer implements ConfigurableFixerInterface
{
  /**
   * @var \Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer
   */
  private $lineLengthFixer;

  /**
   * LineLengthFixer constructor.
   *
   * @param $indent
   * @param $lineEnding
   */
  public function __construct($indent, $lineEnding)
  {
    $whitespacesFixerConfig = new \PhpCsFixer\WhitespacesFixerConfig($indent, $lineEnding);

    $indentDetector = new \Symplify\TokenRunner\Analyzer\FixerAnalyzer\IndentDetector(
      $whitespacesFixerConfig);

    $blockFinder = new \Symplify\TokenRunner\Analyzer\FixerAnalyzer\BlockFinder();

    $tokenSkipper = new \Symplify\TokenRunner\Analyzer\FixerAnalyzer\TokenSkipper(
      $blockFinder);

    $lineLengthTransformer = new \Symplify\TokenRunner\Transformer\FixerTransformer\LineLengthTransformer(
      $indentDetector, $tokenSkipper, $whitespacesFixerConfig);

    $this->lineLengthFixer = new \Symplify\CodingStandard\Fixer\LineLength\LineLengthFixer(
      $lineLengthTransformer, $blockFinder);
 }

  /**
   * {@inheritdoc}
   */
  public function configure(array $configuration = NULL) {
    return $this->lineLengthFixer->configure((array) $configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function fix(SplFileInfo $file, Tokens $tokens)
  {
    return $this->lineLengthFixer->fix($file, $tokens);
  }

  /**
   * {@inheritdoc}
   */
  public function getDefinition()
  {
    return $this->lineLengthFixer->getDefinition();
  }

  /**
   * {@inheritdoc}
   */
  public function getName()
  {
    return 'Drupal/line_length';
  }

  /**
   * {@inheritdoc}
   */
  public function getPriority()
  {
    return $this->lineLengthFixer->getPriority();
  }

  /**
   * {@inheritdoc}
   */
  public function isCandidate(Tokens $tokens)
  {
    return $this->lineLengthFixer->isCandidate($tokens);
  }

  /**
   * {@inheritdoc}
   */
  public function isRisky()
  {
    return $this->lineLengthFixer->isRisky();
  }

  /**
   * {@inheritdoc}
   */
  public function supports(\SplFileInfo $file)
  {
    return $this->lineLengthFixer->supports($file);
  }
}
