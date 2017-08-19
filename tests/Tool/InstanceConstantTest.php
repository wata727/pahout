<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\InstanceConstant;
use Pahout\Hint;
use Pahout\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;

class InstanceConstantTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_get_class_with_constants()
    {
        $code = <<<'CODE'
<?php
$instance = new Hoge();
get_class($instance)::CONSTANT;
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new InstanceConstant());
        $tester->test($root);

        $this->assertCount(1, $tester->hints);
        $this->assertEquals('InstanceConstant', $tester->hints[0]->type);
        $this->assertEquals('You can access class constants from instances.', $tester->hints[0]->message);
        $this->assertEquals('./test.php', $tester->hints[0]->filename);
        $this->assertEquals(3, $tester->hints[0]->lineno);
    }

    public function test_instance_constants()
    {
        $code = <<<'CODE'
<?php
$instance = new Hoge();
$instance::CONSTANT;
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new InstanceConstant());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
