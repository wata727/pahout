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
    fuga();
} catch (C $exn) {
    echo "catch!";
} catch (D $exn) {
    echo "catch!";
}
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new MultipleCatch());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint('MultipleCatch', 'A catch block may specify multiple exceptions.', './test.php', 4),
                new Hint('MultipleCatch', 'A catch block may specify multiple exceptions.', './test.php', 10),
            ],
            $tester->hints
        );
    }

    public function test_catch_multiple_exceptions()
    {
        $code = <<<'CODE'
<?php
try {
    hoge();
} catch (A | B $exn) {
    echo "catch!";
    fuga();
} catch (C | D $exn) {
    echo "catch!";
}
CODE;
        $root = \ast\parse_code($code, 40);

        $tester = PahoutHelper::create(new MultipleCatch());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
