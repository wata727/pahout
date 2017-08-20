<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\RandomInt;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class RandomIntTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_rand()
    {
        $code = <<<'CODE'
<?php
$rand = rand();
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new RandomInt());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'RandomInt',
                    'This function is not cryptographically secure. Consider using `random_int()`, `random_bytes()`, or `openssl_random_pseudo_bytes()` instead.',
                    './test.php',
                    2
                )
            ],
            $tester->hints
        );
    }

    public function test_mt_rand()
    {
        $code = <<<'CODE'
<?php
$rand = mt_rand();
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new RandomInt());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'RandomInt',
                    'This function is not cryptographically secure. Consider using `random_int()`, `random_bytes()`, or `openssl_random_pseudo_bytes()` instead.',
                    './test.php',
                    2
                )
            ],
            $tester->hints
        );
    }

    public function test_random_int()
    {
        $code = <<<'CODE'
<?php
$rand = random_int(0, PHP_INT_MAX);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new RandomInt());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
