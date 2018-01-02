<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\UnneededRegularExpression;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class UnneededRegularExpressionTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_unneeded_regular_expression()
    {
        $code = <<<'CODE'
<?php
if (preg_match("/abc/", $var)) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnneededRegularExpression());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'UnneededRegularExpression',
                    'Using `strpos()` instead of `preg_match()` will be faster.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/UnneededRegularExpression.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_using_complex_regular_expression()
    {
        $code = <<<'CODE'
<?php
if (preg_match("/^[a-z]$/", $var)) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnneededRegularExpression());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }


    public function test_using_match_value()
    {
        $code = <<<'CODE'
<?php
if (preg_match("/abc/", $var, $match)) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnneededRegularExpression());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_using_pattern_modifier()
    {
        $code = <<<'CODE'
<?php
if (preg_match("/abc/i", $var, $match)) {
    something($var);
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnneededRegularExpression());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
