<?php

namespace Pahout\Command;

use Pahout\Pahout;
use Pahout\Formatter;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Check extends Command
{
    protected function configure()
    {
        $this->setName('check')
             ->setDescription('A linter for writing better PHP')
             ->addArgument('files', InputArgument::IS_ARRAY, 'List of file names or directory names to be analyzed')
             ->addOption('php-version', null, InputOption::VALUE_OPTIONAL, 'Target PHP version (default: 7.1.8)', null)
             ->addOption('ignore-tools', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Ignore tool names (default: Nothing to ignore)', null)
             ->addOption('ignore-paths', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Ignore paths (default: vendor)', null)
             ->addOption('vendor', null, InputOption::VALUE_NONE, 'Check vendor directory (default: false)', null)
             ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format (default: pretty, possibles: pretty)', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Logger::getInstance($output)->info('Start Pahout command');

        Config::load($input->getOptions());

        Logger::getInstance()->info('Start instruction');
        Logger::getInstance()->info('files: '.implode(',', $input->getArgument('files')));
        $pahout = new Pahout($input->getArgument('files'));
        $pahout->instruct();
        Logger::getInstance()->info('End instruction');

        Logger::getInstance()->info('Start formatter');
        $formatter = Formatter::create($output, $pahout->files, $pahout->hints, 'pretty');
        $formatter->print();
        Logger::getInstance()->info('End formatter');

        Logger::getInstance()->info('End Pahout command');
    }
}
