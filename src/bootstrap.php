<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use Pahout\Command\Check;

if (extension_loaded('ast')) {
    $ast_version = phpversion('ast');
    if (version_compare($ast_version, '0.1.4', '<')) {
        fprintf(STDERR, "php-ast extension was found. But, Pahout requires php-ast version 0.1.4 or newer. The installed version is $ast_version.\n");
        exit(Check::EXIT_CODE_ERROR);
    }
} else {
    fprintf(STDERR, "php-ast extension could not be found. Pahout requires php-ast version 0.1.4 or newer.\n");
    exit(Check::EXIT_CODE_ERROR);
}

$check = new Check();

$app = new Application('Pahout', '0.0.1');
$app->add($check);
$app->setDefaultCommand($check->getName(), true);
$app->run();
