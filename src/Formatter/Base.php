<?php declare(strict_types=1);

namespace Pahout\Formatter;

use Pahout\Hint;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Formatter abstract class
*/
abstract class Base
{
    /** @var OutputInterface The output interface of symfony console */
    protected $output;

    /** @var string[] List of analyzed file names */
    protected $files = [];

    /** @var Hint[] Hint list obtained as a result of analysis */
    protected $hints = [];

    /**
    * Formatter constructor
    *
    * @param OutputInterface $output The output interface of symfony console.
    * @param string[]        $files  List of analyzed file names.
    * @param Hint[]          $hints  Hint list obtained as a result of analysis.
    */
    public function __construct(OutputInterface $output, array $files, array $hints)
    {
        sort($files);
        usort($hints, function ($a, $b) {
            return strnatcmp($a->filename, $b->filename);
        });

        $this->output = $output;
        $this->files = $files;
        $this->hints = $hints;
    }

    /**
    * Print hints to the console throught output interface of symfony console.
    *
    * @return void
    */
    abstract public function print();
}
