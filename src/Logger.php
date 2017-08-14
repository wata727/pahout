<?php declare(strict_types=1);

namespace Pahout;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;

/**
* Pahout Logger
*
* Generate and provide commonly used the logger.
*/
class Logger
{
    /** @var ConsoleLogger commonly used the logger instance */
    private static $logger;

    /**
    * Generate or provide the single logger instance.
    *
    * @param OutputInterface|null $output The output interface of symfony console. Do not be null for the first time.
    * @return ConsoleLogger the logger instance.
    */
    public static function getInstance(OutputInterface $output = null): ConsoleLogger
    {
        if (!isset(self::$logger)) {
            self::$logger = new ConsoleLogger($output);
        }
        return self::$logger;
    }
}
