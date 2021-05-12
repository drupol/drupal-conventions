<?php

use drupol\PhpCsFixerConfigsDrupal\Config\Drupal7;

$config = new Drupal7();

$rules = $config->getRules();

$rules['header_comment'] = [
    'comment_type' => 'PHPDoc',
    'header' => trim(file_get_contents(__DIR__ . '/../../resource/header.txt')),
    'location' => 'after_declare_strict',
    'separate' => 'both',
];

return $config->setRules($rules);
