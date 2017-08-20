<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\EscapeShellArg;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class EscapeShellArgTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_escapeshellcmd()
    {
        $code = <<<'CODE'
<?php
$filename = 'test.php" "/etc/passwd';
$cmd = "ls \"$filename\"";
$cmd = escapeshellcmd($cmd);
system($cmd);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new EscapeShellArg());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'EscapeShellArg',
                    'This function allows the attacker to pass arbitrary number of arguments.',
                    './test.php',
                    4,
                    Hint::DOCUMENT_LINK.'/EscapeShellArg.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_escapeshellarg()
    {
        $code = <<<'CODE'
<?php
$filename = 'test.php" "/etc/passwd';
$filename = escapeshellarg($filename);
$cmd = "ls $filename";
system($cmd);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new EscapeShellArg());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
