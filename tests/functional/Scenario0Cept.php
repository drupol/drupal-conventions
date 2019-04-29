<?php

$I = new FunctionalTester($scenario);
$I->wantTo('perform actions and see result');

$command = './vendor/bin/php-cs-fixer --allow-risky=yes --config=./config/drupal7/php_cs_fixer.config.php --using-cache=no --verbose fix tests/_output/fixtures/scenario0/bad1.php';

$I->copyDir('tests/functional/fixtures', 'tests/_output/fixtures');
$I->runShellCommand($command);

$I->canSeeFileFound('tests/_output/fixtures/scenario0/bad1.php');
$I->seeFileContentsEqual(file_get_contents('tests/_output/fixtures/scenario0/good1.php'));
