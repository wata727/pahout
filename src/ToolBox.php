<?php declare(strict_types=1);

namespace Pahout;

use Pahout\Tool\Base;
use Pahout\Tool\ArraySyntaxLong;
use Pahout\Tool\ElvisOperator;

/**
* Factory of tools used by Mahout
*/
class ToolBox
{
    /** List of valid tool names used by Mahout */
    public const VALID_TOOLS = [
        'ArraySyntaxLong',
        'SyntaxError',
        'ElvisOperator',
    ];

    /**
    * Factory method that returns list of tool instances matching PHP version.
    *
    * @return Base[] List of tool instances.
    */
    public static function create(): array
    {
        return array_filter([
            new ArraySyntaxLong(),
            new ElvisOperator(),
        ], function ($tool) {
            $klass = get_class($tool);
            $config = Config::getInstance();
            // Activate only tools that are not included in ignore_tools, and whose PHP version is applicable.
            return !in_array($klass::HINT_TYPE, $config->ignore_tools)
                     && version_compare($config->php_version, $klass::PHP_VERSION, '>=');
        });
    }
}
