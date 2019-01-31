<?php

$finder = PhpCsFixer\Finder::create()
  ->in($_SERVER['PWD'])
  ->files()
  ->name('*.inc')
  ->name('*.install')
  ->name('*.module')
  ->name('*.profile')
  ->name('*.php')
  ->name('*.theme')
  ->ignoreDotFiles(true)
  ->ignoreVCS(true)
  ->exclude(['build', 'libraries', 'node_modules', 'vendor']);

return \drupol\DrupalConventions\PhpCsFixer\Config\Drupal8::create()
  ->setFinder($finder);
