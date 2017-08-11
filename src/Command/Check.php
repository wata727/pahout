<?php
namespace Pahout\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Check extends Command
{
    protected function configure()
    {
        $this->setName('check')
             ->setDescription('Check legacy PHP code');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Awesome output!");
    }
}
