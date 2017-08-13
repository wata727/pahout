<?php

namespace Pahout;

use Pahout\Formatter\Base;
use Pahout\Formatter\Pretty;
use Symfony\Component\Console\Output\OutputInterface;

class Formatter
{
    public const VALID_FORMATS = ['pretty'];

    public static function create(OutputInterface $output, array $files, array $hints, string $type): Base
    {
        switch ($type) {
            case 'pretty':
                return new Pretty($output, $files, $hints);
            default:
                return new Pretty($output, $files, $hints);
        }
    }
}
