<?php declare(strict_types=1);

namespace Pahout;

use Pahout\Formatter\Base;
use Pahout\Formatter\Pretty;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Formatter factory for displaying hints in the console.
*/
class Formatter
{
    /** List of valid format names */
    public const VALID_FORMATS = ['pretty'];

    /**
    * Factory method that returns the specified formatter.
    *
    * @param OutputInterface $output The output interface of symfony console.
    * @param string[]        $files  List of analyzed file names.
    * @param Hint[]          $hints  Hint list obtained as a result of analysis.
    * @param string          $type   Name of formatter type.
    * @return Base The specified formatter instance.
    */
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
