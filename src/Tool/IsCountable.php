<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Hint;

/**
*
*/
class IsCountable implements Base
{
    use Howdah;

    public const ENTRY_POINT = \ast\AST_BINARY_OP;
    public const PHP_VERSION = '7.3.0';
    public const HINT_TYPE = "IsCountable";
    private const HINT_MESSAGE = 'You can use `is_countable` function instead.';
    private const HINT_LINK = Hint::DOCUMENT_LINK."/IsCountable.md";

    /**
    * Check if there is `is_array($a) || $a instanceof Countable` regardless order
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        if ($node->flags !== \ast\flags\BINARY_BOOL_OR) {
            return [];
        }

        if ($this->isArrayAndInstanceOfCountable($node->children['left'], $node->children['right'])
             || $this->isArrayAndInstanceOfCountable($node->children['right'], $node->children['left'])) {
            return [new Hint(
                self::HINT_TYPE,
                self::HINT_MESSAGE,
                $file,
                $node->lineno,
                self::HINT_LINK
            )];
        }

        return [];
    }

    /**
    * Check if there is `is_array($a)` and `$a instanceof Countable` expr
    *
    * @param mixed $left  A node to be analyzed.
    * @param mixed $right The other one.
    * @return boolean Result.
    */
    private function isArrayAndInstanceOfCountable($left, $right): Bool
    {
        if (!($left instanceof Node && $right instanceof Node)) {
            return false;
        }

        if (!$this->isFunctionCall($left, 'is_array')) {
            return false;
        }
        $args = $left->children['args'];

        if ($args->kind !== \ast\AST_ARG_LIST) {
            return false;
        }
        $arg = $args->children[0];

        if ($right->kind !== \ast\AST_INSTANCEOF) {
            return false;
        }
        $expr = $right->children['expr'];
        $class = $right->children['class'];

        if ($class->kind === \ast\AST_NAME
              && $class->children['name'] === "Countable"
              && $this->isEqualsWithoutLineno($arg, $expr)) {
            return true;
        }

        return false;
    }
}
