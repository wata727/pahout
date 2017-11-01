<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\DuplicateCaseCondition;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class DuplicateCaseConditionTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_duplicate_case_condition()
    {
        $code = <<<'CODE'
<?php
switch ($a) {
    case 1:
        echo "hoge";
        break;
    case 1:
        echo "fuga";
        break;
    default:
        echo "meow";
        break;
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DuplicateCaseCondition());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'DuplicateCaseCondition',
                    'Duplicate case condition found.',
                    './test.php',
                    6,
                    Hint::DOCUMENT_LINK.'/DuplicateCaseCondition.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_duplicate_case_condition_node()
    {
        $code = <<<'CODE'
<?php
switch ($a) {
    case $b:
        echo "hoge";
        break;
    case $b:
        echo "fuga";
        break;
    default:
        echo "meow";
        break;
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DuplicateCaseCondition());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'DuplicateCaseCondition',
                    'Duplicate case condition found.',
                    './test.php',
                    6,
                    Hint::DOCUMENT_LINK.'/DuplicateCaseCondition.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_unduplicate_key()
    {
        $code = <<<'CODE'
<?php
switch ($a) {
    case 1:
        echo "hoge";
        break;
    case 2:
        echo "fuga";
        break;
    default:
        echo "meow";
        break;
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new DuplicateCaseCondition());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
