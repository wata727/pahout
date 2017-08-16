<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect the ternary operator that can use the Elvis operator.
*
* As of PHP 5.3, you can use the "elvis operator".
* You can avoid redundant expressions by using this.
*
* ## Before
*
* ```
* <?php
* $a = $a ? $a : false;
* ```
*
* ## After
*
* ```
* <?php
* $a = $a ?: false;
* ```
*/
class ElvisOperator implements Base
{
    /** Analyze ternary oprator node (AST_CONDITIONAL) */
    public const ENTRY_POINT = \ast\AST_CONDITIONAL;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '5.3.0';
    public const HINT_TYPE = "ElvisOperator";
    private const HINT_MESSAGE = 'Use elvis operator instead of ternary operator.';

    /**
    * Detects ternary operators that have the same condition expression and true expression.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint|null A Hint obtained from results. If it does not exist, it returns null.
    */
    public function run(string $file, Node $node): ?Hint
    {
        $cond = $node->children['cond'];
        $true = $node->children['true'];
        // If both are Node (Object), using the comparison operator.
        if (is_object($cond) && is_object($true)) {
            if ($cond != $true) {
                Logger::getInstance()->debug('Different node found. Ignore it: '.$node->lineno);
                return null;
            }
        // If it is not an object, using the identity operator.
        } else {
            if ($cond !== $true) {
                Logger::getInstance()->debug('Ignore: '.$node->lineno);
                return null;
            }
        }

        return new Hint(
            self::HINT_TYPE,
            self::HINT_MESSAGE,
            $file,
            $node->lineno
        );
    }
}
