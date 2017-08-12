<?php

namespace Pahout\Command;

use Pahout\Pahout;
use Pahout\Formatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Check extends Command
{
    protected function configure()
    {
        $this->setName('check')
             ->addArgument('files', InputArgument::IS_ARRAY, 'List of file names or directory names to be analyzed');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pahout = new Pahout($input->getArgument('files'));
        $pahout->instruct();

        $formatter = Formatter::create($output, $pahout->files, $pahout->hints, 'pretty');
        $formatter->print();
    }
}
