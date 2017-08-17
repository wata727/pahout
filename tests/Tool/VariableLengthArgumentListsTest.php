<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\VariableLengthArgumentLists;
use Pahout\Hint;
use Pahout\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;

class VariableLengthArgumentListsTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_func_get_args()
    {
        $code = <<<'CODE'
<?php
function sum() {
    $acc = 0;
    foreach (func_get_args() as $n) {
        $acc += $n;
    }
    return $acc;
}
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new VariableLengthArgumentLists());
        $tester->test($root);

        $this->assertCount(1, $tester->hints);
        $this->assertEquals('VariableLengthArgumentLists', $tester->hints[0]->type);
        $this->assertEquals('Using variable length arguments may make it unnecessary to use `func_num_args()`, `func_get_arg()` and `func_get_args()`.', $tester->hints[0]->message);
        $this->assertEquals('./test.php', $tester->hints[0]->filename);
        $this->assertEquals(4, $tester->hints[0]->lineno);
    }

    public function test_func_get_arg()
    {
        $code = <<<'CODE'
<?php
function get_first_arg() {
    return func_get_arg(0);
}
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new VariableLengthArgumentLists());
        $tester->test($root);

        $this->assertCount(1, $tester->hints);
        $this->assertEquals('VariableLengthArgumentLists', $tester->hints[0]->type);
        $this->assertEquals('Using variable length arguments may make it unnecessary to use `func_num_args()`, `func_get_arg()` and `func_get_args()`.', $tester->hints[0]->message);
        $this->assertEquals('./test.php', $tester->hints[0]->filename);
        $this->assertEquals(3, $tester->hints[0]->lineno);
    }

    public function test_func_num_args()
    {
        $code = <<<'CODE'
<?php
function get_arguments_count() {
    return func_num_args();
}
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new VariableLengthArgumentLists());
        $tester->test($root);

        $this->assertCount(1, $tester->hints);
        $this->assertEquals('VariableLengthArgumentLists', $tester->hints[0]->type);
        $this->assertEquals('Using variable length arguments may make it unnecessary to use `func_num_args()`, `func_get_arg()` and `func_get_args()`.', $tester->hints[0]->message);
        $this->assertEquals('./test.php', $tester->hints[0]->filename);
        $this->assertEquals(3, $tester->hints[0]->lineno);
    }

    public function test_veriable_length_argument_lists()
    {
        $code = <<<'CODE'
<?php
function sum(...$numbers) {
    $acc = 0;
    foreach ($numbers as $n) {
        $acc += $n;
    }
    return $acc;
}
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new VariableLengthArgumentLists());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
