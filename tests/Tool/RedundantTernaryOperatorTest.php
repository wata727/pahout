<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\RedundantTernaryOperator;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class RedundantTernaryOperatorTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_redundant_ternary_operator_with_equal()
    {
        $code = <<<'CODE'
<?php
$a = $a == $b ? true : false;
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new RedundantTernaryOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'RedundantTernaryOperator',
                    'There is no need to use the ternary operator.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/RedundantTernaryOperator.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_redundant_ternary_operator_with_identical()
    {
        $code = <<<'CODE'
<?php
$a = $a === $b ? true : false;
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new RedundantTernaryOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'RedundantTernaryOperator',
                    'There is no need to use the ternary operator.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/RedundantTernaryOperator.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_redundant_ternary_operator_with_not_equal()
    {
        $code = <<<'CODE'
<?php
$a = $a != $b ? true : false;
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new RedundantTernaryOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'RedundantTernaryOperator',
                    'There is no need to use the ternary operator.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/RedundantTernaryOperator.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_redundant_ternary_operator_with_not_identical()
    {
        $code = <<<'CODE'
<?php
$a = $a !== $b ? true : false;
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new RedundantTernaryOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'RedundantTernaryOperator',
                    'There is no need to use the ternary operator.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/RedundantTernaryOperator.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_simple_binary_operator()
    {
        $code = <<<'CODE'
<?php
$a = $a === $b;
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new RedundantTernaryOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_ternary_operator_with_function()
    {
        $code = <<<'CODE'
<?php
$a = something() ? true : false;
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new RedundantTernaryOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
