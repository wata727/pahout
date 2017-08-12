<?php

namespace Pahout\Command;

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
        $analyzer = new \Pahout\Analyzer($input->getArgument('files'));
        $analyzer->run();

        var_dump($analyzer->warnings);
    }
}
