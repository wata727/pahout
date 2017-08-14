<?php declare(strict_types=1);

namespace Pahout;

use Pahout\Tool\ArraySyntaxLong;
use Pahout\Tool\Base;
use Pahout\Config;

/**
* Factory of tools used by Mahout
*/
class Tool
{
    /** List of valid tool names used by Mahout */
    public const VALID_TOOLS = ['ArraySyntaxLong'];

    /**
    * Factory method that returns list of tool instances matching PHP version.
    *
    * @return Base[] List of tool instances.
    */
    public static function create(): array
    {
        return array_filter([
            new ArraySyntaxLong()
        ], function ($tool) {
            // Filter tools that do not match PHP version.
            return version_compare(Config::getInstance()->php_version, get_class($tool)::PHP_VERSION, '>=');
        });
    }
}
