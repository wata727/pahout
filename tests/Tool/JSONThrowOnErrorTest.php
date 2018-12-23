<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\JSONThrowOnError;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class JSONThrowOnErrorTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_json_decode_without_options()
    {
        $code = <<<'CODE'
<?php
json_decode($json);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'JSONThrowOnError',
                    'Encourage to specify JSON_THROW_ON_ERROR option.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/JSONThrowOnError.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_json_decode_with_json_throw_on_error_options()
    {
        $code = <<<'CODE'
<?php
json_decode($json, false, 512, JSON_THROW_ON_ERROR);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_json_decode_with_other_options()
    {
        $code = <<<'CODE'
<?php
json_decode($json, false, 512, JSON_BIGINT_AS_STRING);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'JSONThrowOnError',
                    'Encourage to specify JSON_THROW_ON_ERROR option.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/JSONThrowOnError.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_json_decode_with_multiple_other_options()
    {
        $code = <<<'CODE'
<?php
json_decode($json, false, 512, JSON_BIGINT_AS_STRING | JSON_OBJECT_AS_ARRAY);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'JSONThrowOnError',
                    'Encourage to specify JSON_THROW_ON_ERROR option.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/JSONThrowOnError.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_json_decode_with_multiple_json_throw_on_error_options()
    {
        $code = <<<'CODE'
<?php
json_decode($json, false, 512, JSON_BIGINT_AS_STRING | JSON_OBJECT_AS_ARRAY | JSON_THROW_ON_ERROR);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_json_decode_with_variable_options()
    {
        $code = <<<'CODE'
<?php
json_decode($json, false, 512, $options);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_json_encode_without_options()
    {
        $code = <<<'CODE'
<?php
json_encode($json);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'JSONThrowOnError',
                    'Encourage to specify JSON_THROW_ON_ERROR option.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/JSONThrowOnError.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_json_encode_with_json_throw_on_error_options()
    {
        $code = <<<'CODE'
<?php
json_encode($json, JSON_THROW_ON_ERROR);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_json_encode_with_other_options()
    {
        $code = <<<'CODE'
<?php
json_encode($json, JSON_FORCE_OBJECT);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'JSONThrowOnError',
                    'Encourage to specify JSON_THROW_ON_ERROR option.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/JSONThrowOnError.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_json_encode_with_multiple_other_options()
    {
        $code = <<<'CODE'
<?php
json_encode($json, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'JSONThrowOnError',
                    'Encourage to specify JSON_THROW_ON_ERROR option.',
                    './test.php',
                    2,
                    Hint::DOCUMENT_LINK.'/JSONThrowOnError.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_json_encode_with_multiple_json_throw_on_error_options()
    {
        $code = <<<'CODE'
<?php
json_encode($json, JSON_FORCE_OBJECT | JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_json_encode_with_variable_options()
    {
        $code = <<<'CODE'
<?php
json_encode($json, $options);
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new JSONThrowOnError());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
