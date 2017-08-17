<?php declare(strict_types=1);

namespace Pahout\Formatter;

/**
* Human-readable output formatter
*
* Following example:
*
* ```
* ./subdir/test.php:4
*     ShortArraySyntax: Use [...] syntax instead of array(...) syntax.
*
* ./test.php:3
*     ShortArraySyntax: Use [...] syntax instead of array(...) syntax.
*
* 2 files checked, 2 hints detected.
* ```
*/
class Pretty extends Base
{
    /**
    * Print hints to the console throught output interface of symfony console.
    *
    * If there is no hints, a message of blessing will be displayed.
    *
    * @return void
    */
    public function print()
    {
        // If there is no hints, print a message for that.
        if (count($this->hints) === 0) {
            $this->output->writeln('<fg=black;bg=green>Awesome! There is nothing from me to teach you!</>');
            $this->output->write("\n");
        } else {
            foreach ($this->hints as $hint) {
                $this->output->writeln('<info>'.$hint->filename.':'.$hint->lineno.'</>');
                $this->output->writeln("\t".$hint->type.': '.$hint->message);
                $this->output->write("\n");
            }
        }

        $this->output->writeln(count($this->files).' files checked, '.count($this->hints).' hints detected.');
    }
}
