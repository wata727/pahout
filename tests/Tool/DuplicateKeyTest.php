<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\DuplicateKey;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class DuplicateKeyTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_duplicate_key()
    {
        $code = <<<'CODE'
<?php
$array = [
    "a" => 1,
    "a" => 2,
];
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DuplicateKey());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'DuplicateKey',
                    'Duplicate key found in array.',
                    './test.php',
                    4,
                    Hint::DOCUMENT_LINK.'/DuplicateKey.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_duplicate_node_key()
    {
        $code = <<<'CODE'
<?php
$array = [
    $test => 1,
    $test => 2
];
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DuplicateKey());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'DuplicateKey',
                    'Duplicate key found in array.',
                    './test.php',
                    4,
                    Hint::DOCUMENT_LINK.'/DuplicateKey.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_unduplicate_key()
    {
        $code = <<<'CODE'
<?php
$array = [
    "a" => 1,
    "b" => 2
];
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DuplicateKey());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
