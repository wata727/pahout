<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect list array syntax.
*
* In PHP 7.1 and later, short array syntax can be used as an alternative to `list()`.
*
* ## Before
*
* ```
* <?php
* list($a, $b) = $array;
* ```
*
* ## After
*
* ```
* <?php
* [$a, $b] = $array;
* ```
*/
class SymmetricArrayDestructuring implements Base
{
    /** Analyze array declarations node (AST_ARRAY) */
    const ENTRY_POINT = \ast\AST_ARRAY;
    /** PHP version to enable this tool */
    const PHP_VERSION = '7.1.0';
    const HINT_TYPE = "SymmetricArrayDestructuring";
    const HINT_MESSAGE = "Use [...] syntax instead of list(...) syntax.";
    const HINT_LINK = Hint::DOCUMENT_LINK."/SymmetricArrayDestructuring.md";

    /**
    * Detect ARRAY_SYNTAX_LIST node.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        if ($node->flags !== \ast\flags\ARRAY_SYNTAX_LIST) {
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
