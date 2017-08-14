<?php declare(strict_types=1);

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

/**
* Pahout Command
*
* Pahout CLI using the Symfony console.
*
* https://symfony.com/doc/current/components/console.html
*/
class Check extends Command
{
    /**
    * Define commands and other options.
    *
    * Default values ​​are not specified for configuration merging.
    *
    * @return void
    */
    protected function configure()
    {
        $this->setName('check')
             ->setDescription('A linter for writing better PHP')
             ->addArgument('files', InputArgument::IS_ARRAY, 'List of file names or directory names to be analyzed')
             ->addOption(
                 'php-version',
                 null,
                 InputOption::VALUE_OPTIONAL,
                 'Target PHP version <comment>[default: "7.1.8"]</>',
                 null
             )
             ->addOption(
                 'ignore-tools',
                 null,
                 InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                 'Ignore tool types <comment>[default: Nothing to ignore]</>',
                 null
             )
             ->addOption(
                 'ignore-paths',
                 null,
                 InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                 'Ignore files and directories <comment>[default: Nothing to ignore]</>',
                 null
             )
             ->addOption(
                 'vendor',
                 null,
                 InputOption::VALUE_NONE,
                 'Check vendor directory <comment>[default: false]</>',
                 null
             )
             ->addOption(
                 'format',
                 'f',
                 InputOption::VALUE_OPTIONAL,
                 'Output format <comment>[default: "pretty", possibles: "pretty"]</>',
                 null
             )
             ->addOption(
                 'config',
                 'c',
                 InputOption::VALUE_OPTIONAL,
                 'Config file path',
                 '.pahout.yaml'
             );
    }

    /**
    * CLI execution entrypoint
    *
    * It is executed in the following steps.
    *
    * 1. Setup config.
    * 2. Parse PHP scripts and traverse AST nodes.
    * 3. Print hints.
    *
    * @param InputInterface  $input  The input interface of symfony console.
    * @param OutputInterface $output The output interface of symfony console.
    * @return integer|null
    */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Logger::getInstance($output)->info('Start Pahout command');

        Config::load($input->getOptions(), $input->getOption('config'));

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

        return null;
    }
}
