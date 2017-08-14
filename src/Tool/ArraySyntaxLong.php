<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Tool\Base;
use Pahout\Hint;

/**
* Detect old array syntax.
*
* As of PHP 5.4 you can also use the short array syntax.
* There is no reason other than compatibility to keep using old syntax.
*
* ## Before
*
* ```
* <?php
* $a = array(1, 2, 3);
* ```
*
* ## After
*
* ```
* <?php
* $a = [1, 2, 3];
* ```
*/
class ArraySyntaxLong implements Base
{
    /** Analyze array declarations node (AST_ARRAY) */
    public const ENTRY_POINT = \ast\AST_ARRAY;
    public const HINT_TYPE = "array_syntax_long";
    private const HINT_MESSAGE = "Use [...] syntax instead of array(...) syntax.";

    /**
    * Detect ARRAY_SYNTAX_LONG node.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint|null A Hint obtained from results. If it does not exist, it returns null.
    */
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
