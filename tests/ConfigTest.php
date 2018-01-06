<?php

namespace Pahout\Test;

use PHPUnit\Framework\TestCase;
use Pahout\Config;
use Pahout\Logger;
use Pahout\Exception\InvalidConfigFilePathException;
use Pahout\Exception\InvalidConfigOptionException;
use Pahout\Exception\InvalidConfigOptionValueException;
use Symfony\Component\Console\Output\ConsoleOutput;

class ConfigTest extends TestCase
{
    const FIXTURE_PATH = __DIR__.'/fixtures';

    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_default_config()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_vendor');

            Config::load([
                'php-version' => null,
                'ignore-tools' => null,
                'ignore-paths' => null,
                'vendor' => null,
                'format' => null,
            ]);
            $config = Config::getInstance();

            $this->assertEquals('7.1.8', $config->php_version);
            $this->assertEmpty($config->ignore_tools);
            $this->assertEquals([
                self::FIXTURE_PATH.'/with_vendor/vendor/test.php'
            ], $config->ignore_paths);
            $this->assertFalse($config->vendor);
            $this->assertEquals('pretty', $config->format);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_specified_config()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_config_file');

            Config::load([
                'php-version' => '7.1.0',
                'ignore-tools' => ['ShortArraySyntax'],
                'ignore-paths' => ['tests'],
                'vendor' => true,
                'format' => 'json',
            ]);
            $config = Config::getInstance();

            $this->assertEquals('7.1.0', $config->php_version);
            $this->assertEquals(['ShortArraySyntax'], $config->ignore_tools);
            $this->assertEquals([
                self::FIXTURE_PATH.'/with_config_file/tests/test1.php'
            ], $config->ignore_paths);
            $this->assertTrue($config->vendor);
            $this->assertEquals('json', $config->format);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_php_version_file()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_php-version');

            Config::load([
                'php-version' => null,
                'ignore-tools' => null,
                'ignore-paths' => null,
                'vendor' => null,
                'format' => null,
            ]);
            $config = Config::getInstance();

            $this->assertEquals('7.0.0', $config->php_version);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_php_version_file_with_arguments()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_php-version');

            Config::load([
                'php-version' => '7.1.0',
                'ignore-tools' => null,
                'ignore-paths' => null,
                'vendor' => null,
                'format' => null,
            ]);
            $config = Config::getInstance();

            $this->assertEquals('7.1.0', $config->php_version);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_with_config_file()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_config_file');

            Config::load([
                'php-version' => null,
                'ignore-tools' => null,
                'ignore-paths' => null,
                'vendor' => null,
                'format' => null,
            ], 'custom_config.yaml');
            $config = Config::getInstance();

            $this->assertEquals('7.0.0', $config->php_version);
            $this->assertEquals(['ShortArraySyntax'], $config->ignore_tools);
            $this->assertEquals([
                self::FIXTURE_PATH.'/with_config_file/tests/test1.php',
                self::FIXTURE_PATH.'/with_config_file/bin/test1.php',
                self::FIXTURE_PATH.'/with_config_file/bin/test2.php',
            ], $config->ignore_paths);
            $this->assertTrue($config->vendor);
            $this->assertEquals('pretty', $config->format);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_specified_config_with_config_file()
    {
        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_config_file');

            Config::load([
                'php-version' => '7.1.0',
                'ignore-tools' => ['SyntaxError'],
                'ignore-paths' => ['tests'],
                'vendor' => null,
                'format' => null,
            ], 'custom_config.yaml');
            $config = Config::getInstance();

            $this->assertEquals('7.1.0', $config->php_version);
            $this->assertEquals(['SyntaxError'], $config->ignore_tools);
            $this->assertEquals([
                self::FIXTURE_PATH.'/with_config_file/tests/test1.php'
            ], $config->ignore_paths);
            $this->assertTrue($config->vendor);
            $this->assertEquals('pretty', $config->format);
        } finally {
            chdir($work_dir);
        }
    }

    public function test_throw_exception_when_specified_config_file_not_found()
    {
        $this->expectException(InvalidConfigFilePathException::class);
        $this->expectExceptionMessage('`invalid_config_file.yaml` is not found.');

        Config::load([
            'php-version' => null,
            'ignore-tools' => null,
            'ignore-paths' => null,
            'vendor' => null,
            'format' => null,
        ], 'invalid_config_file.yaml');
    }

    public function test_throw_exception_when_include_invalid_key_in_config_file()
    {
        $this->expectException(InvalidConfigOptionException::class);
        $this->expectExceptionMessage('`invalid` is an invalid option.');

        $work_dir = getcwd();
        try {
            chdir(self::FIXTURE_PATH.'/with_config_file');

            Config::load([
                'php-version' => null,
                'ignore-tools' => null,
                'ignore-paths' => null,
                'vendor' => null,
                'format' => null,
            ], 'invalid_config.yaml');
        } finally {
            chdir($work_dir);
        }
    }

    public function test_throw_exception_when_specified_an_invalid_version()
    {
        $this->expectException(InvalidConfigOptionValueException::class);
        $this->expectExceptionMessage('`7.1` is an invalid PHP version. Please specify the correct version such as `7.1.8`.');

        Config::load([
            'php-version' => '7.1',
            'ignore-tools' => null,
            'ignore-paths' => null,
            'vendor' => null,
            'format' => null,
        ]);
    }

    public function test_throw_exception_when_specified_an_invalid_tools()
    {
        $this->expectException(InvalidConfigOptionValueException::class);
        $this->expectExceptionMessage('`invalid_tool` is an invalid tool. Please check the correct tool list.');

        Config::load([
            'php-version' => null,
            'ignore-tools' => ['invalid_tool'],
            'ignore-paths' => null,
            'vendor' => null,
            'format' => null,
        ]);
    }

    public function test_throw_exception_when_specified_an_invalid_paths()
    {
        $this->expectException(InvalidConfigOptionValueException::class);
        $this->expectExceptionMessage('`tests` is invalid paths. It must be array.');

        Config::load([
            'php-version' => null,
            'ignore-tools' => null,
            'ignore-paths' => 'tests',
            'vendor' => null,
            'format' => null,
        ]);
    }

    public function test_throw_exception_when_specified_an_invalid_vendor()
    {
        $this->expectException(InvalidConfigOptionValueException::class);
        $this->expectExceptionMessage('`yes` is an invalid vendor flag. It must be `true` or `false`.');

        Config::load([
            'php-version' => null,
            'ignore-tools' => null,
            'ignore-paths' => null,
            'vendor' => 'yes',
            'format' => null,
        ]);
    }

    public function test_throw_exception_when_specified_an_invalid_format()
    {
        $this->expectException(InvalidConfigOptionValueException::class);
        $this->expectExceptionMessage('`xml` is an invalid format. It must be `pretty` or `json`.');

        Config::load([
            'php-version' => null,
            'ignore-tools' => null,
            'ignore-paths' => null,
            'vendor' => null,
            'format' => 'xml',
        ]);
    }
}
