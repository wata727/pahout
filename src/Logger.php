<?php declare(strict_types=1);

namespace Pahout;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

class Logger
{
    private static $logger;

    public static function getInstance(OutputInterface $output = null): ConsoleLogger
    {
        if (!isset(self::$logger)) {
            self::$logger = new ConsoleLogger($output);
        }
        return self::$logger;
    }
}
