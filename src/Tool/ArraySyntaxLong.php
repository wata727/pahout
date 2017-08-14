<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Tool\Base;
use Pahout\Hint;

class ArraySyntaxLong implements Base
{
    public const ENTRY_POINT = \ast\AST_ARRAY;
    public const HINT_TYPE = "array_syntax_long";
    private const HINT_MESSAGE = "Use [...] syntax instead of array(...) syntax.";

    public function run(string $file, Node $node): ?Hint
    {
        if ($node->flags !== \ast\flags\ARRAY_SYNTAX_LONG) {
            Logger::getInstance()->debug('Ignore flags: '.$node->flags);
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
