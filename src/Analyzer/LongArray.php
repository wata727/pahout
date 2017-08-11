<?php

namespace Pahout\Analyzer;

use Pahout\Analyzer\Base;
use Pahout\Warning;

class LongArray implements Base
{
    public const ENTRY_POINT = \ast\AST_ARRAY;
    private const WARNING_TYPE = "long_array";
    private const WARNING_MESSAGE = "Use [...] syntax instead of array(...) syntax.";

    public function run(\ast\Node $node): ?Warning
    {
        if ($node->flags !== \ast\flags\ARRAY_SYNTAX_LONG) {
            return null;
        }

        return new Warning(
            self::WARNING_TYPE,
            self::WARNING_MESSAGE,
            $node->lineno
        );
    }
}
