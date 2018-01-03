<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\AmbiguousReturnCheck;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class AmbiguousReturnCheckTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_function_call()
    {
        $code = <<<'CODE'
<?php
if (strpos($var, "a")) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new AmbiguousReturnCheck());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'AmbiguousReturnCheck',
                    'Use the === (or !==) operator for testing the return value of `strpos`.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/AmbiguousReturnCheck.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_unary_bool_not()
    {
        $code = <<<'CODE'
<?php
if (!strpos($var, "a")) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new AmbiguousReturnCheck());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'AmbiguousReturnCheck',
                    'Use the === (or !==) operator for testing the return value of `strpos`.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/AmbiguousReturnCheck.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_equal_operator()
    {
        $code = <<<'CODE'
<?php
if (strpos($var, "a") == false) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new AmbiguousReturnCheck());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'AmbiguousReturnCheck',
                    'Use the === (or !==) operator for testing the return value of `strpos`.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/AmbiguousReturnCheck.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_not_equal_operator()
    {
        $code = <<<'CODE'
<?php
if (strpos($var, "a") != false) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new AmbiguousReturnCheck());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'AmbiguousReturnCheck',
                    'Use the === (or !==) operator for testing the return value of `strpos`.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/AmbiguousReturnCheck.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_identical_operator()
    {
        $code = <<<'CODE'
<?php
if (strpos($var, "a") === false) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new AmbiguousReturnCheck());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_not_identical_operator()
    {
        $code = <<<'CODE'
<?php
if (strpos($var, "a") !== false) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new AmbiguousReturnCheck());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
