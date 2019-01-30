<?php

$finder = PhpCsFixer\Finder::create()
  ->in($_SERVER['PWD'])
  ->files()
  ->name('*.inc')
  ->name('*.install')
  ->name('*.module')
  ->name('*.profile')
  ->name('*.php')
  ->ignoreDotFiles(true)
  ->ignoreVCS(true)
  ->exclude(['build', 'libraries', 'node_modules', 'vendor']);

return PhpCsFixer\Config::create()
  ->registerCustomFixers(
    array(
      new drupol\DrupalConventions\PhpCsFixer\Fixer\UppercaseConstantsFixer(),
      new drupol\DrupalConventions\PhpCsFixer\Fixer\InlineCommentSpacerFixer(),
    )
  )
  ->setIndent('    ')
  ->setLineEnding("\n")
  ->setFinder($finder);
