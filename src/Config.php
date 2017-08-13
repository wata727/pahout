<?php

namespace Pahout;

use Pahout\Logger;
use Pahout\Formatter;
use Pahout\Exception\InvalidConfigFilePathException;
use Pahout\Exception\InvalidConfigOptionException;
use Pahout\Exception\InvalidConfigOptionValueException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Input\InputInterface;

class Config
{
    private const DEFAULT_FILE_PATH = './.pahout.yaml';

    private static $config;
    public $php_version;
    public $ignore_tools;
    public $ignore_paths;
    public $ignore_vendor;
    public $format;

    public static function load(array $arguments, string $file = self::DEFAULT_FILE_PATH)
    {
        self::$config = new Config();
        self::setOption('php_version', $arguments['php_version'] ?? '7.1.8');
        self::setOption('ignore_tools', $arguments['ignore_tools'] ?? []);
        self::setOption('ignore_paths', $arguments['ignore_paths'] ?? []);
        self::setOption('ignore_vendor', $arguments['ignore_vendor'] ?? true);
        self::setOption('format', $arguments['format'] ?? 'pretty');

        if (is_file($file)) {
            Logger::getInstance()->info('Load: '.$file);
            $config_yaml = Yaml::parse(file_get_contents($file));
            foreach ($config_yaml as $key => $value) {
                self::setOption($key, $value);
            }
        } elseif ($file !== self::DEFAULT_FILE_PATH) {
            throw new InvalidConfigFilePathException($file.' is not found.');
        } else {
            Logger::getInstance()->info(self::DEFAULT_FILE_PATH.' is not found.');
        }
    }

    public static function getInstance()
    {
        return self::$config;
    }

    private static function setOption(string $key, $value)
    {
        switch ($key) {
            case 'php_version':
                if (preg_match('/^[0-9]\.[0-9]\.[0-9]$/', $value) !== 1) {
                    throw new InvalidConfigOptionValueException(
                        $value.' is an invalid PHP version. Please specify the correct version such as `7.1.8`.'
                    );
                }
                self::$config->php_version = $value;
                break;
            case 'ignore_tools':
                if (!is_array($value)) {
                    throw new InvalidConfigOptionValueException($value.' is invalid tools. It must be array.');
                }
                foreach ($value as $tool) {
                    if (!in_array($tool, Tool::VALID_TOOLS, true)) {
                        throw new InvalidConfigOptionValueException(
                            $tool.' is an invalid tool. Please check the correct tool list.'
                        );
                    }
                }
                self::$config->ignore_tools = $value;
                break;
            case 'ignore_paths':
                if (!is_array($value)) {
                    throw new InvalidConfigOptionValueException($value.' is invalid paths. It must be array.');
                }
                self::$config->ignore_paths = $value;
                break;
            case 'ignore_vendor':
                if (!is_bool($value)) {
                    throw new InvalidConfigOptionValueException(
                        $value.' is an invalid ignore_vendor flag. It must be `true` or `false`.'
                    );
                }
                self::$config->ignore_vendor = $value;
                break;
            case 'format':
                if (!in_array($value, Formatter::VALID_FORMATS, true)) {
                    throw new InvalidConfigOptionValueException($value.' is an invalid format. It must be `pretty`.');
                }
                self::$config->format = $value;
                break;
            default:
                throw new InvalidConfigOptionException($key.' is an invalid option.');
                break;
        }
    }
}
