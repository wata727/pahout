<?php

namespace Pahout\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Output\ConsoleOutput;
use Pahout\Command\Check;
use Pahout\Logger;
use Pahout\Exception\InvalidFilePathException;

class IntegrationTest extends TestCase
{
    private const FIXTURE_PATH = __DIR__.'/fixtures';

    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_loads_current_dir_when_not_receiving_any_files()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/not_receiving_any_files');
            $command = new CommandTester(new Check());
            $command->execute([]);
            $output = $command->getDisplay();

            $expected = <<<OUTPUT
./subdir/test.php:4
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

./syntax_error.php:3
\tSyntaxError: syntax error, unexpected 'f' (T_STRING)

./test.php:3
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

3 files checked, 3 hints detected.

OUTPUT;

            $this->assertEquals($expected, $output);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_loads_specified_files_and_dirs_when_receiving_files_and_dirs()
    {
        $command = new CommandTester(new Check());
        $command->execute([
            'files' => [
                self::FIXTURE_PATH.'/receiving_files_and_dirs/subdir',
                self::FIXTURE_PATH.'/receiving_files_and_dirs/test1.php',
                self::FIXTURE_PATH.'/receiving_files_and_dirs/test3.php',
            ]
        ]);
        $output = $command->getDisplay();

        $file1 = self::FIXTURE_PATH.'/receiving_files_and_dirs/subdir/test.php';
        $file2 = self::FIXTURE_PATH.'/receiving_files_and_dirs/test1.php';
        $file3 = self::FIXTURE_PATH.'/receiving_files_and_dirs/test3.php';
        $expected = <<<OUTPUT
$file1:3
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

$file2:3
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

$file3:3
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

3 files checked, 3 hints detected.

OUTPUT;

        $this->assertEquals($expected, $output);
    }

    public function test_throws_an_exception_when_receiving_files_that_do_not_exist()
    {
        $this->expectException(InvalidFilePathException::class);
        $this->expectExceptionMessage('notfound is neither a file nor a directory.');

        $command = new CommandTester(new Check());
        $command->execute(['files' => ['notfound']]);
    }

    public function test_when_specified_old_php_version()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/not_receiving_any_files');
            $command = new CommandTester(new Check());
            $command->execute(['--php-version' => '5.3.3']);
            $output = $command->getDisplay();

            $expected = <<<OUTPUT
./syntax_error.php:3
\tSyntaxError: syntax error, unexpected identifier (T_STRING)

3 files checked, 1 hints detected.

OUTPUT;

            $this->assertEquals($expected, $output);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_when_specified_ignore_tools()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/not_receiving_any_files');
            $command = new CommandTester(new Check());
            $command->execute([
                '--ignore-tools' => ['ArraySyntaxLong', 'SyntaxError']
            ]);
            $output = $command->getDisplay();

            $expected = <<<OUTPUT
Awesome! There is nothing from me to teach you!

3 files checked, 0 hints detected.

OUTPUT;

            $this->assertEquals($expected, $output);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_when_specified_ignore_paths()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/not_receiving_any_files');
            $command = new CommandTester(new Check());
            $command->execute([
                '--ignore-paths' => ['subdir']
            ]);
            $output = $command->getDisplay();

            $expected = <<<OUTPUT
./syntax_error.php:3
\tSyntaxError: syntax error, unexpected identifier (T_STRING)

./test.php:3
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

2 files checked, 2 hints detected.

OUTPUT;

            $this->assertEquals($expected, $output);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_with_default_config_file()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_default_config_file');
            $command = new CommandTester(new Check());
            $command->execute([]);
            $output = $command->getDisplay();

            $expected = <<<OUTPUT
Awesome! There is nothing from me to teach you!

1 files checked, 0 hints detected.

OUTPUT;

            $this->assertEquals($expected, $output);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_with_custom_config_file()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_default_config_file');
            $command = new CommandTester(new Check());
            $command->execute(['--config' => 'custom_pahout.yaml']);
            $output = $command->getDisplay();

            $expected = <<<OUTPUT
./test.php:3
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

1 files checked, 1 hints detected.

OUTPUT;

            $this->assertEquals($expected, $output);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_vendor_with_default()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_vendor');
            $command = new CommandTester(new Check());
            $command->execute([]);
            $output = $command->getDisplay();

            $expected = <<<OUTPUT
./test.php:3
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

1 files checked, 1 hints detected.

OUTPUT;

            $this->assertEquals($expected, $output);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_vendor_with_enabled_flag()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_vendor');
            $command = new CommandTester(new Check());
            $command->execute(['--vendor' => 'true']);
            $output = $command->getDisplay();

            $expected = <<<OUTPUT
./test.php:3
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

./vendor/test.php:3
\tArraySyntaxLong: Use [...] syntax instead of array(...) syntax.

2 files checked, 2 hints detected.

OUTPUT;

            $this->assertEquals($expected, $output);
        } finally {
            chdir($work_dir);
        }
    }
}
