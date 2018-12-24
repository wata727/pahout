<?php declare(strict_types=1);

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\ElvisOperator;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class ElvisOperatorTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_ternary_operator_with_same_node()
    {
        $root = \ast\parse_code('<?php $a = $a ? $a : false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'ElvisOperator',
                    'Use the Elvis operator instead of the ternary operator.',
                    './test.php',
                    1,
                    Hint::DOCUMENT_LINK.'/ElvisOperator.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_ternary_operator_with_same_string()
    {
        $root = \ast\parse_code('<?php $a = \'a\' ? \'a\' : false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'ElvisOperator',
                    'Use the Elvis operator instead of the ternary operator.',
                    './test.php',
                    1,
                    Hint::DOCUMENT_LINK.'/ElvisOperator.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_ternary_operator_with_different_node()
    {
        $root = \ast\parse_code('<?php $a = $a ? $b : false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_ternary_operator_with_different_type()
    {
        $root = \ast\parse_code('<?php $a = $a ? 1 : false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_elvis_operator()
    {
        $root = \ast\parse_code('<?php $a = $a ?: false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
