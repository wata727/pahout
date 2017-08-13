<?php

namespace Pahout\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Pahout\Command\Check;

class CommandTest extends TestCase
{
    private const FIXTURE_PATH = __DIR__.'/fixtures';

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
\tarray_syntax_long: Use [...] syntax instead of array(...) syntax.

./test.php:3
\tarray_syntax_long: Use [...] syntax instead of array(...) syntax.

2 files checked, 2 hints detected.

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
\tarray_syntax_long: Use [...] syntax instead of array(...) syntax.

$file2:3
\tarray_syntax_long: Use [...] syntax instead of array(...) syntax.

$file3:3
\tarray_syntax_long: Use [...] syntax instead of array(...) syntax.

3 files checked, 3 hints detected.

OUTPUT;

        $this->assertEquals($expected, $output);
    }
}