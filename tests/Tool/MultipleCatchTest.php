<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\MultipleCatch;
use Pahout\Hint;
use Pahout\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;

class MultipleCatchTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_catch_single_exception()
    {
        $code = <<<'CODE'
<?php
try {
    hoge();
} catch (A $exn) {
    echo "catch!";
    fuga();
} catch (B $exn) {
    echo "catch!";
} catch (C $exn) {
    echo "catch!";
}
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new MultipleCatch());
        $tester->test($root);

        $this->assertCount(1, $tester->hints);
        $this->assertEquals('MultipleCatch', $tester->hints[0]->type);
        $this->assertEquals('A catch block may specify multiple exceptions.', $tester->hints[0]->message);
        $this->assertEquals('./test.php', $tester->hints[0]->filename);
        $this->assertEquals(7, $tester->hints[0]->lineno);
    }

    public function test_catch_multiple_exceptions()
    {
        $code = <<<'CODE'
<?php
try {
    hoge();
} catch (A $exn) {
    echo "catch!";
    fuga();
} catch (B | C $exn) {
    echo "catch!";
}
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new MultipleCatch());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
