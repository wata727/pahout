<?php declare(strict_types=1);

namespace Pahout;

use Pahout\Tool\Base;

/**
* Factory of tools used by Mahout
*/
class ToolBox
{
    /** List of valid tool names used by Mahout */
    public const VALID_TOOLS = [
        'ShortArraySyntax',
        'SyntaxError',
        'ElvisOperator',
        'NullCoalescingOperator',
        'VariableLengthArgumentLists',
        'InstanceConstant',
    ];

    /**
    * Factory method that returns list of tool instances matching PHP version.
    *
    * @return Base[] List of tool instances.
    */
    public static function create(): array
    {
        $tools = [];
        foreach (self::VALID_TOOLS as $tool) {
            // SyntaxError is a special tool. Pahout directly generates Hint without checking AST.
            if ($tool !== 'SyntaxError') {
                $klass = "Pahout\\Tool\\".$tool;
                $tools[] = new $klass();
            }
        }

        return array_filter($tools, function ($tool) {
            $config = Config::getInstance();
            // Activate only tools that are not included in ignore_tools, and whose PHP version is applicable.
            return !in_array($tool::HINT_TYPE, $config->ignore_tools)
                     && version_compare($config->php_version, $tool::PHP_VERSION, '>=');
        });
    }
}
