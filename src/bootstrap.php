<?php

require_once __DIR__ . '/requirements.php';

use Symfony\Component\Console\Application;
use Pahout\Command\Check;

$check = new Check();

$app = new Application('Pahout', '0.5.0');
$app->add($check);
$app->setDefaultCommand($check->getName(), true);
$app->run();
