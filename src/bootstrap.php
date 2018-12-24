<?php declare(strict_types=1);

require_once __DIR__ . '/requirements.php';

use Symfony\Component\Console\Application;
use Pahout\Command\Check;

$check = new Check();

$app = new Application('Pahout', '0.5.1');
$app->add($check);
$app->setDefaultCommand($check->getName(), true);
$app->run();
