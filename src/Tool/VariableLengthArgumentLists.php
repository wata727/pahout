<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect function calls replaced with variable-length argument lists.
*
* In PHP 5.6 and later, it has support for variable-length argument lists in user-defined functions.
* Previously, we needed `func_num_args()`, `func_get_arg()` and `func_get_args()` to handle variable-length arguments, but it is no longer necessary.
*
* ## Before
*
* ```
* <?php
* function sum() {
*     $acc = 0;
*     foreach (func_get_args() as $n) {
*         $acc += $n;
*     }
*     return $acc;
* }
* ```
*
* ## After
*
* ```
* <?php
* function sum(...$numbers) {
*     $acc = 0;
*     foreach ($numbers as $n) {
*         $acc += $n;
*     }
*     return $acc;
* }
* ```
*/
class VariableLengthArgumentLists implements Base
{
    /** Analyze function call declarations (AST_CALL) */
    public const ENTRY_POINT = \ast\AST_CALL;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '5.6.0';
    public const HINT_TYPE = "VariableLengthArgumentLists";
    private const HINT_MESSAGE = "Using variable length arguments may make it unnecessary to use `func_num_args()`, `func_get_arg()` and `func_get_args()`.";
    private const FUNCTION_LIST = ['func_num_args', 'func_get_arg', 'func_get_args'];

    /**
    * Detect func_num_args(), func_get_arg() and func_get_args() function call.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $expr = $node->children['expr'];

        if ($expr->kind === \ast\AST_NAME) {
            if (in_array($expr->children['name'], self::FUNCTION_LIST, true)) {
                return [new Hint(
                    self::HINT_TYPE,
                    self::HINT_MESSAGE,
                    $file,
                    $node->lineno
                )];
            } else {
                Logger::getInstance()->debug('Ignore function name: '.$expr->children['name']);
            }
        } else {
            Logger::getInstance()->debug('Ignore AST kind: '.$expr->kind);
        }

        return [];
    }
}
