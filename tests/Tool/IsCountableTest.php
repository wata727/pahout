<?php declare(strict_types=1);

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\IsCountable;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class IsCountableTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_is_array_and_instanceof_countable()
    {
        $code = <<<'CODE'
<?php
$obj = get_object();
if (is_array($obj) || $obj instanceof Countable) {
    // Something
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new IsCountable());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'IsCountable',
                    'You can use `is_countable` function instead.',
                    './test.php',
                    3,
                    Hint::DOCUMENT_LINK.'/IsCountable.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_instanceof_countable_and_is_array()
    {
        $code = <<<'CODE'
<?php
$obj = get_object();
if ($obj instanceof Countable || is_array($obj)) {
    // Something
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new IsCountable());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'IsCountable',
                    'You can use `is_countable` function instead.',
                    './test.php',
                    3,
                    Hint::DOCUMENT_LINK.'/IsCountable.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_instanceof_countable_and_is_array_with_other_variables()
    {
        $code = <<<'CODE'
<?php
$obj = get_object();
$obj2 = get_object();
if ($obj instanceof Countable || is_array($obj2)) {
    // Something
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new IsCountable());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_is_countable()
    {
        $code = <<<'CODE'
<?php
$obj = get_object();
if (is_countable($obj)) {
    // Something
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new IsCountable());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
