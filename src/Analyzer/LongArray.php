<?php

namespace Pahout\Analyzer;

use Pahout\Analyzer\Base;
use Pahout\Hint;

class LongArray implements Base
{
    public const ENTRY_POINT = \ast\AST_ARRAY;
    private const HINT_TYPE = "long_array";
    private const HINT_MESSAGE = "Use [...] syntax instead of array(...) syntax.";

    public function run(string $file, \ast\Node $node): ?Hint
    {
        if ($node->flags !== \ast\flags\ARRAY_SYNTAX_LONG) {
            return null;
        }

        return new Hint(
            self::HINT_TYPE,
            self::HINT_MESSAGE,
            $file,
            $node->lineno
        );
    }
}
