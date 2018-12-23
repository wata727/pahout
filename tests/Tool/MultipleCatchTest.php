<?php declare(strict_types=1);

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\MultipleCatch;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class MultipleCatchTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_redundant_catch_block()
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
    fuga();
} catch (D $exn) {
    echo "catch!";
} catch (E $exn) {
    echo "catch!";
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new MultipleCatch());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'MultipleCatch',
                    'Specifying `A`, `B` and `C` in this catch block avoids redundant catch blocks.',
                    './test.php',
                    4,
                    Hint::DOCUMENT_LINK.'/MultipleCatch.md'
                ),
                new Hint(
                    'MultipleCatch',
                    'Specifying `D` and `E` in this catch block avoids redundant catch blocks.',
                    './test.php',
                    10,
                    Hint::DOCUMENT_LINK.'/MultipleCatch.md'
                ),
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
} catch (A | B | C $exn) {
    echo "catch!";
    fuga();
} catch (C | D $exn) {
    echo "catch!";
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new MultipleCatch());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
