<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\SymmetricArrayDestructuring;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class SymmetricArrayDestructuringTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_array_syntax_list()
    {
        $code = <<<'CODE'
<?php
list($a, $b) = $array;
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new SymmetricArrayDestructuring());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint('SymmetricArrayDestructuring', 'Use [...] syntax instead of list(...) syntax.', './test.php', 2)
            ],
            $tester->hints
        );
    }

    public function test_array_syntax_short()
    {
        $code = <<<'CODE'
<?php
[$a, $b] = $array;
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new SymmetricArrayDestructuring());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
