<?php declare(strict_types=1);

namespace Pahout;

use Pahout\Tool\ArraySyntaxLong;
use Pahout\Tool\Base;

/**
* Factory of tools used by Mahout
*/
class Tool
{
    /** List of valid tool names used by Mahout */
    public const VALID_TOOLS = ['ArraySyntaxLong'];

    /**
    * Factory method that returns list of tool instances.
    *
    * @return Base[] List of tool instances.
    */
    public static function create(): array
    {
        return [
            new ArraySyntaxLong()
        ];
    }
}
