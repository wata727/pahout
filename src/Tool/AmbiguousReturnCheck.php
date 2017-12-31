<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect a testing of return value of function not using the `===` operator.
*
* In functions returning falsy value, do not compare return values ​​without using the `===` operator.
* For example, `strpos` returns integer or false, so if you check this with the `==` operator,
* when returning 0, the condition is the same as false.
*
* ## Before
*
* ```
* if (strpos($var, "a")) {
*     echo "`a` is found.";
* }
* ```
*
* ## After
*
* ```
* if (strpos($var, "a") !== false) {
*     echo "`a` is found.";
* }
* ```
*
*/
class AmbiguousReturnCheck implements Base
{
    use Howdah;

    public const ENTRY_POINT = \ast\AST_IF_ELEM;
    public const PHP_VERSION = '0.0.0';
    public const HINT_TYPE = "AmbiguousReturnCheck";
    private const HINT_MESSAGE = "Use the === operator for testing a function that returns falsy value.";
    private const HINT_LINK = Hint::DOCUMENT_LINK."/AmbiguousReturnCheck.md";
    private const FUNCTION_LIST = [
        'strpos',
        'mb_strpos',
        'stripos',
        'mb_stripos',
        'strrpos',
        'mb_strrpos',
        'array_search'
    ];

    /**
    * Detect a testing of return value of function not using the `===` operator.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $hints = [];

        $cond = $node->children['cond'];
        if (!($cond instanceof Node)) {
            return [];
        }

        foreach (self::FUNCTION_LIST as $function) {
            // if (strpos(var, str))
            if ($this->isFunctionCall($cond, $function)) {
                $hints[] = new Hint(
                    self::HINT_TYPE,
                    self::HINT_MESSAGE,
                    $file,
                    $cond->lineno,
                    self::HINT_LINK
                );
            }

            // if (!strpos(var, str))
            if ($cond->kind === \ast\AST_UNARY_OP && $cond->flags === \ast\flags\UNARY_BOOL_NOT) {
                $expr = $cond->children['expr'];

                if ($this->isFunctionCall($expr, $function)) {
                    $hints[] = new Hint(
                        self::HINT_TYPE,
                        self::HINT_MESSAGE,
                        $file,
                        $expr->lineno,
                        self::HINT_LINK
                    );
                }
            }

            // if (strpos(var, str) == val || val == strpos(var, str))
            // if (strpos(var, str) != val || val != strpos(var, str))
            if ($cond->kind === \ast\AST_BINARY_OP && in_array($cond->flags, [\ast\flags\BINARY_IS_EQUAL, \ast\flags\BINARY_IS_NOT_EQUAL], true)) {
                $left = $cond->children['left'];
                $right = $cond->children['right'];

                if ($this->isFunctionCall($left, $function) || $this->isFunctionCall($right, $function)) {
                    $hints[] = new Hint(
                        self::HINT_TYPE,
                        self::HINT_MESSAGE,
                        $file,
                        $cond->lineno,
                        self::HINT_LINK
                    );
                }
            }
        }

        return $hints;
    }
}
