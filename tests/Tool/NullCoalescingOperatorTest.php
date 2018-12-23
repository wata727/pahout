<?php declare(strict_types=1);

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\NullCoalescingOperator;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class NullCoalescingOperatorTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_ternary_operator_with_isset_node()
    {
        $root = \ast\parse_code('<?php $a = isset($b) ? $b : false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'NullCoalescingOperator',
                    'Use the null coalecing operator instead of the ternary operator.',
                    './test.php',
                    1,
                    Hint::DOCUMENT_LINK.'/NullCoalescingOperator.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_ternary_operator_with_isset_string()
    {
        $root = \ast\parse_code('<?php $a = isset("a") ? "a" : false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'NullCoalescingOperator',
                    'Use the null coalecing operator instead of the ternary operator.',
                    './test.php',
                    1,
                    Hint::DOCUMENT_LINK.'/NullCoalescingOperator.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_ternary_operator_with_different_node()
    {
        $root = \ast\parse_code('<?php $a = isset($b) ? $c : false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_ternary_operator_with_different_type()
    {
        $root = \ast\parse_code('<?php $a = isset($b) ? 1 : false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_null_coalescing_operator()
    {
        $root = \ast\parse_code('<?php $a = $b ?? false; ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
