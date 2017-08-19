<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect the ternary operator that can use the Null coalescing operator.
*
* As of PHP 7, you can use the "null coalescing operator".
* You can avoid redundant expressions by using this.
*
* ## Before
*
* ```
* <?php
* $a = isset($b) ? $b : false;
* ```
*
* ## After
*
* ```
* <?php
* $a = $b ?? false;
* ```
*/
class NullCoalescingOperator implements Base
{
    /** Analyze ternary operator node (AST_CONDITIONAL) */
    public const ENTRY_POINT = \ast\AST_CONDITIONAL;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '7.0.0';
    public const HINT_TYPE = "NullCoalescingOperator";
    private const HINT_MESSAGE = 'Use null coalecing operator instead of ternary operator.';

    /**
    * Detects ternary operators with isset().
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $cond = $node->children['cond'];
        $true = $node->children['true'];

        if ($cond instanceof Node && $cond->kind === \ast\AST_ISSET) {
            $var = $cond->children['var'];

            // If both are Node (Object), using the comparison operator.
            if (is_object($var) && is_object($true)) {
                if ($var != $true) {
                    Logger::getInstance()->debug('Different node found. Ignore it: '.$node->lineno);
                    return [];
                }
            // If it is not an object, using the identity operator.
            } else {
                if ($var !== $true) {
                    Logger::getInstance()->debug('Ignore: '.$node->lineno);
                    return [];
                }
            }

            return [new Hint(
                self::HINT_TYPE,
                self::HINT_MESSAGE,
                $file,
                $node->lineno
            )];
        }

        return [];
    }
}
