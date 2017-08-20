<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect long array syntax.
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
class ShortArraySyntax implements Base
{
    /** Analyze array declarations node (AST_ARRAY) */
    public const ENTRY_POINT = \ast\AST_ARRAY;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '5.4.0';
    public const HINT_TYPE = "ShortArraySyntax";
    private const HINT_MESSAGE = "Use [...] syntax instead of array(...) syntax.";
    private const HINT_LINK = Hint::DOCUMENT_LINK."/ShortArraySyntax.md";

    /**
    * Detect ARRAY_SYNTAX_LONG node.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        if ($node->flags !== \ast\flags\ARRAY_SYNTAX_LONG) {
            Logger::getInstance()->debug('Ignore flags: '.$node->flags);
            return [];
        }

        return [new Hint(
            self::HINT_TYPE,
            self::HINT_MESSAGE,
            $file,
            $node->lineno,
            self::HINT_LINK
        )];
    }
}
