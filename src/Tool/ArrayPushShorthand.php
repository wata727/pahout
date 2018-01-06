<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect `array_push()` with wasteful function call overhead.
*
* If you use `array_push()` to add one element to the array it's better to use `$array[] =` because in that way there is no overhead of calling a function.
*
* ## Before
*
* ```
* <?php
* array_push($array, 1);
* ```
*
* ## After
*
* ```
* <?php
* $array[] = 1;
* ```
*/
class ArrayPushShorthand implements Base
{
    use Howdah;

    /** Analyze function call declarations (AST_CALL) */
    public const ENTRY_POINT = \ast\AST_CALL;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '0.0.0';
    public const HINT_TYPE = "ArrayPushShorthand";
    private const HINT_MESSAGE = 'Since `array_push()` has the function call overhead, Consider using `$array[] =`.';
    private const HINT_LINK = Hint::DOCUMENT_LINK."/ArrayPushShorthand.md";

    /**
    * Detect array_push() with single element.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        if ($this->isFunctionCall($node, 'array_push')) {
            $args = $node->children['args'];
            $second_arg = $args->children[1];
            // If using `...$list` pattern, skip this tool.
            if ($second_arg instanceof Node && $second_arg->kind === \ast\AST_UNPACK) {
                return [];
            }
            if (count($args->children) === 2) {
                return [new Hint(
                    self::HINT_TYPE,
                    self::HINT_MESSAGE,
                    $file,
                    $node->lineno,
                    self::HINT_LINK
                )];
            }
        }

        return [];
    }
}
