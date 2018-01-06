<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\ArrayPushShorthand;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class ArrayPushShorthandTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_array_push_with_single_element()
    {
        $code = <<<'CODE'
<?php
array_push($array, 1);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new ArrayPushShorthand());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'ArrayPushShorthand',
                    'Since `array_push()` has the function call overhead, Consider using `$array[] =`.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/ArrayPushShorthand.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_array_push_with_multiple_elements()
    {
        $code = <<<'CODE'
<?php
array_push($array, 1, 2);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new ArrayPushShorthand());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_array_push_with_unpack_elements()
    {
        $code = <<<'CODE'
<?php
array_push($array, ...$list);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new ArrayPushShorthand());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_square_bracket_syntax()
    {
        $code = <<<'CODE'
<?php
$array[] = 1;
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new ArrayPushShorthand());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
