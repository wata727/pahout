<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\ElvisOperator;
use Pahout\Hint;
use Pahout\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;

class ElvisOperatorTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_ternary_operator_with_same_node()
    {
        $root = \ast\parse_code('<?php $a = $a ? $a : false; ?>', 40);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertCount(1, $tester->hints);
        $this->assertEquals('ElvisOperator', $tester->hints[0]->type);
        $this->assertEquals('Use elvis operator instead of ternary operator.', $tester->hints[0]->message);
        $this->assertEquals('./test.php', $tester->hints[0]->filename);
        $this->assertEquals(1, $tester->hints[0]->lineno);
    }

    public function test_ternary_operator_with_same_string()
    {
        $root = \ast\parse_code('<?php $a = \'a\' ? \'a\' : false; ?>', 40);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertCount(1, $tester->hints);
        $this->assertEquals('ElvisOperator', $tester->hints[0]->type);
        $this->assertEquals('Use elvis operator instead of ternary operator.', $tester->hints[0]->message);
        $this->assertEquals('./test.php', $tester->hints[0]->filename);
        $this->assertEquals(1, $tester->hints[0]->lineno);
    }

    public function test_ternary_operator_with_different_node()
    {
        $root = \ast\parse_code('<?php $a = $a ? $b : false; ?>', 40);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_ternary_operator_with_different_type()
    {
        $root = \ast\parse_code('<?php $a = $a ? 1 : false; ?>', 40);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_elvis_operator()
    {
        $root = \ast\parse_code('<?php $a = $a ?: false; ?>', 40);

        $tester = PahoutHelper::create(new ElvisOperator());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
