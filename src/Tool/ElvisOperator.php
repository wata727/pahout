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
    /** Analyze ternary operator node (AST_CONDITIONAL) */
    const ENTRY_POINT = \ast\AST_CONDITIONAL;
    /** PHP version to enable this tool */
    const PHP_VERSION = '5.3.0';
    const HINT_TYPE = "ElvisOperator";
    const HINT_MESSAGE = 'Use the Elvis operator instead of the ternary operator.';
    const HINT_LINK = Hint::DOCUMENT_LINK."/ElvisOperator.md";

    /**
    * Detects ternary operators that have the same condition expression and true expression.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $cond = $node->children['cond'];
        $true = $node->children['true'];
        // If both are Node (Object), using the comparison operator.
        if (is_object($cond) && is_object($true)) {
            if ($cond != $true) {
                Logger::getInstance()->debug('Different node found. Ignore it: '.$node->lineno);
                return [];
            }
        // If it is not an object, using the identity operator.
        } else {
            if ($cond !== $true) {
                Logger::getInstance()->debug('Ignore: '.$node->lineno);
                return [];
            }
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
