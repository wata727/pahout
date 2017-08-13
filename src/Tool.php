<?php

namespace Pahout;

use Pahout\Tool\ArraySyntaxLong;

class Tool
{
    public const VALID_TOOLS = ['array_syntax_long'];

    public static function create(): array
    {
        return [
            new ArraySyntaxLong()
        ];
    }
}
