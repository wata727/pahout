<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\PasswordHash;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class PasswordHashTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_crypt()
    {
        $code = <<<'CODE'
<?php
$password = crypt('secret text', generate_salt());
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new PasswordHash());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'PasswordHash',
                    'Use of `password_hash()` is encouraged.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/PasswordHash.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_password_hash()
    {
        $code = <<<'CODE'
<?php
$password = password_hash('secret text', PASSWORD_DEFAULT);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new PasswordHash());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
