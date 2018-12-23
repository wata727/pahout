<?php declare(strict_types=1);

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\DeclareStrictTypes;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class DeclareStrictTypesTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_without_strict_types_declaration()
    {
        $code = <<<'CODE'
<?php

function sum(int $a, int $b) {
    return $a + $b;
}

var_dump(sum(1, 2));
var_dump(sum(1.5, 2.5));
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DeclareStrictTypes());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'DeclareStrictTypes',
                    'Missing strict type declaration. The default types are not strict.',
                    './test.php',
                    1,
                    Hint::DOCUMENT_LINK.'/DeclareStrictTypes.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_with_strict_types_1_declaration()
    {
        $code = <<<'CODE'
<?php
declare(strict_types=1);

function sum(int $a, int $b) {
    return $a + $b;
}

var_dump(sum(1, 2));
var_dump(sum(1.5, 2.5));
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DeclareStrictTypes());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_with_strict_types_0_declaration()
    {
        $code = <<<'CODE'
<?php
declare(strict_types=0);

function sum(int $a, int $b) {
    return $a + $b;
}

var_dump(sum(1, 2));
var_dump(sum(1.5, 2.5));
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DeclareStrictTypes());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_with_ticks_declaration()
    {
        $code = <<<'CODE'
<?php
declare(ticks=1);

function sum(int $a, int $b) {
    return $a + $b;
}

var_dump(sum(1, 2));
var_dump(sum(1.5, 2.5));
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DeclareStrictTypes());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'DeclareStrictTypes',
                    'Missing strict type declaration. The default types are not strict.',
                    './test.php',
                    1,
                    Hint::DOCUMENT_LINK.'/DeclareStrictTypes.md'
                )
            ],
            $tester->hints
        );
    }
}
