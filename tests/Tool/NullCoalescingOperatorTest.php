<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\NullCoalescingOperator;
use Pahout\Hint;
use Pahout\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;

class NullCoalescingOperatorTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_ternary_operator_with_isset_node()
    {
        $root = \ast\parse_code('<?php $a = isset($b) ? $b : false; ?>', 40);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint('NullCoalescingOperator', 'Use null coalecing operator instead of ternary operator.', './test.php', 1)
            ],
            $tester->hints
        );
    }

    public function test_ternary_operator_with_isset_string()
    {
        $root = \ast\parse_code('<?php $a = isset("a") ? "a" : false; ?>', 40);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint('NullCoalescingOperator', 'Use null coalecing operator instead of ternary operator.', './test.php', 1)
            ],
            $tester->hints
        );
    }

    public function test_ternary_operator_with_different_node()
    {
        $root = \ast\parse_code('<?php $a = isset($b) ? $c : false; ?>', 40);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_ternary_operator_with_different_type()
    {
        $root = \ast\parse_code('<?php $a = isset($b) ? 1 : false; ?>', 40);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_null_coalescing_operator()
    {
        $root = \ast\parse_code('<?php $a = $b ?? false; ?>', 40);

        $tester = PahoutHelper::create(new NullCoalescingOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
