<?php declare(strict_types=1);

namespace Pahout\Formatter;

use Pahout\Formatter\Base;

class Pretty extends Base
{
    public function print()
    {
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
