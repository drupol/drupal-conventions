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

return \drupol\DrupalConventions\PhpCsFixer\Config\Drupal7::create()
  ->setFinder($finder);
