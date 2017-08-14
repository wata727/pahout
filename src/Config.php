<?php declare(strict_types=1);

namespace Pahout;

use Pahout\Logger;
use Pahout\Formatter;
use Pahout\Loader;
use Pahout\Exception\InvalidConfigFilePathException;
use Pahout\Exception\InvalidConfigOptionException;
use Pahout\Exception\InvalidConfigOptionValueException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Input\InputInterface;

/**
* Pahout Config
*
* Merge the configuration file and argument setting items to generate the appropriate settings.
* It also sets default values ​​and validate the values.
* Since settings are commonly handled as one instance, it is implemented with a singleton pattern.
*/
class Config
{
    /** The name of the configuration file to load by default. */
    private const DEFAULT_FILE_PATH = '.pahout.yaml';

    /** @var Config the single config instancec */
    private static $config;

    /** @var string Target PHP version. default is latest version */
    public $php_version = '7.1.8';

    /** @var string[] Ignore tool types */
    public $ignore_tools = [];

    /** @var string[] Ignore files or directories */
    public $ignore_paths = [];

    /** @var bool Check vendor directory */
    public $vendor = false;

    /** @var string The name of formatter */
    public $format = 'pretty';

    /**
    * Merge the configuration file, arguments and default values ​​and set the configuration instance.
    *
    * It receives arguments and filenames and merges them with default values.
    * Priorities are as follows:
    *
    * 1. Received arguments
    * 2. Configuration file
    * 3. Default values
    *
    * If the configuration file does not exist, an exception is thrown,
    * but if it is a default file name it will not throw an exception.
    *
    * @param string[] $arguments Received arguments.
    * @param string   $file      The name of configuration file.
    * @throws InvalidConfigFilePathException Exception where the specified configuration file does not exist.
    * @throws InvalidConfigOptionException   Exception when setting nonexistent config option.
    * @return void
    */
    public static function load(array $arguments, string $file = self::DEFAULT_FILE_PATH)
    {
        // Generate default config instance.
        self::$config = new Config();

        // If received file name is valid file, parses this file.
        if (is_file($file)) {
            Logger::getInstance()->info('Load: '.$file);
            $config_yaml = Yaml::parse(file_get_contents($file));
            if (is_iterable($config_yaml)) {
                foreach ($config_yaml as $key => $value) {
                    // `format` can not be specified from the configuration file.
                    if ($key === 'format') {
                        throw new InvalidConfigOptionException('`format` is an invalid option in config file.');
                    }
                    self::setOption($key, $value);
                }
            // If object is not iterable, It judges that this configuration file is invalid.
            } else {
                throw new InvalidConfigFilePathException('`'.$file.'` is not a valid YAML.');
            }
        // If the configuration file name does not exist and is not the default, throw an exception.
        } elseif ($file !== self::DEFAULT_FILE_PATH) {
            throw new InvalidConfigFilePathException('`'.$file.'` is not found.');
        // If the configuration file name does not exist and is the default, does not throw an exception.
        } else {
            Logger::getInstance()->info(self::DEFAULT_FILE_PATH.' is not found.');
        }

        // If the arguments are given, set it.
        if ($arguments['php-version']) {
            self::setOption('php_version', $arguments['php-version']);
        }
        if ($arguments['ignore-tools']) {
            self::setOption('ignore_tools', $arguments['ignore-tools']);
        }
        if ($arguments['ignore-paths']) {
            self::setOption('ignore_paths', $arguments['ignore-paths']);
        }
        if ($arguments['vendor']) {
            self::setOption('vendor', $arguments['vendor']);
        }
        if ($arguments['format']) {
            self::setOption('format', $arguments['format']);
        }

        // If disabled vendor flag, add `vendor` directory to ignore paths.
        if (!self::$config->vendor) {
            self::$config->ignore_paths[] = 'vendor';
        }

        // Resolve ignore_paths to file name and reset.
        self::$config->ignore_paths = array_map(function ($path) {
            return realpath($path);
        }, Loader::dig(self::$config->ignore_paths));

        Logger::getInstance()->info('PHP version: '.self::$config->php_version);
        Logger::getInstance()->info('Ignore tools: '.var_export(self::$config->ignore_tools, true));
        Logger::getInstance()->info('Ignore paths: '.var_export(self::$config->ignore_paths, true));
        Logger::getInstance()->info('Vendor: '.var_export(self::$config->vendor, true));
        Logger::getInstance()->info('Format: '.self::$config->format);
    }

    /**
    * Get the single config instance.
    *
    * @return Config the single config instance.
    */
    public static function getInstance(): Config
    {
        return self::$config;
    }

    /**
    * Set a option value to config instance and validate a value.
    *
    * @param string $key   Option key.
    * @param mixed  $value Option value.
    * @throws InvalidConfigOptionValueException Exception when invalid value is specified in config option.
    * @throws InvalidConfigOptionException      Exception when setting nonexistent config option.
    * @return void
    */
    private static function setOption(string $key, $value)
    {
        switch ($key) {
            // PHP version format is must have a format like `7.1.8`
            case 'php_version':
                if (preg_match('/^[0-9]\.[0-9]\.[0-9]$/', $value) !== 1) {
                    throw new InvalidConfigOptionValueException(
                        '`'.$value.'` is an invalid PHP version. Please specify the correct version such as `7.1.8`.'
                    );
                }
                self::$config->php_version = $value;
                break;
            // Ignore tools is must be array of valid tool types.
            case 'ignore_tools':
                if (!is_array($value)) {
                    throw new InvalidConfigOptionValueException('`'.$value.'` is invalid tools. It must be array.');
                }
                foreach ($value as $tool) {
                    if (!in_array($tool, Tool::VALID_TOOLS, true)) {
                        throw new InvalidConfigOptionValueException(
                            '`'.$tool.'` is an invalid tool. Please check the correct tool list.'
                        );
                    }
                }
                self::$config->ignore_tools = $value;
                break;
            // Ignore paths is must be array of files or directories.
            case 'ignore_paths':
                if (!is_array($value)) {
                    throw new InvalidConfigOptionValueException('`'.$value.'` is invalid paths. It must be array.');
                }
                self::$config->ignore_paths = $value;
                break;
            // Vendor flag must be boolean.
            case 'vendor':
                if ($value === 'true') {
                    $value = true;
                }
                if ($value === 'false') {
                    $value = false;
                }
                if (!is_bool($value)) {
                    throw new InvalidConfigOptionValueException(
                        '`'.$value.'` is an invalid vendor flag. It must be `true` or `false`.'
                    );
                }
                self::$config->vendor = $value;
                break;
            // Format is must be valid name of formatter.
            case 'format':
                if (!in_array($value, Formatter::VALID_FORMATS, true)) {
                    throw new InvalidConfigOptionValueException(
                        '`'.$value.'` is an invalid format. It must be `pretty`.'
                    );
                }
                self::$config->format = $value;
                break;
            // If received unknown option key, throw an exception.
            default:
                throw new InvalidConfigOptionException('`'.$key.'` is an invalid option.');
                break;
        }
    }
}
