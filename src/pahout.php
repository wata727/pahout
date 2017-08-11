<?php declare(strict_types=1);

require_once 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use Pahout\Command\Check;

$app = new Application('Pahout', '0.0.1');
$app->add(new Check());
$app->run();
