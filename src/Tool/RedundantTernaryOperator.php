<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Hint;

/**
* Detect redundant ternary operator.
*
* If the ternary operator returns boolean, depending on the condition expression, it is not necessary to use the ternary operator.
*
* ## Before
*
* ```
* <?php
* $a = $b === $c ? true : false;
* ```
*
* ## After
*
* ```
* <?php
* $a = $b === $c;
* ```
*
*/
class RedundantTernaryOperator implements Base
{
    /** Analyze ternary operator node (AST_CONDITIONAL) */
    const ENTRY_POINT = \ast\AST_CONDITIONAL;
    /** PHP version to enable this tool */
    const PHP_VERSION = '0.0.0';
    const HINT_TYPE = "RedundantTernaryOperator";
    const HINT_MESSAGE = 'There is no need to use the ternary operator.';
    const HINT_LINK = Hint::DOCUMENT_LINK."/RedundantTernaryOperator.md";

    /**
    * Detect the following ternary operator:
    *   `$a === $b ? true : false`
    *   `$a == $b ? true : false`
    *   `$a !== $b ? true : false`
    *   `$a != $b ? true : false`
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $cond = $node->children['cond'];
        $true = $node->children['true'];
        $false = $node->children['false'];

        if ($cond instanceof Node && $cond->kind === \ast\AST_BINARY_OP) {
            // `$a === $b ? true : false` or `$a == $b ? true : false`
            if ($cond->flags === \ast\flags\BINARY_IS_IDENTICAL || $cond->flags === \ast\flags\BINARY_IS_EQUAL) {
                if ($this->isTrueNode($true) && $this->isFalseNode($false)) {
                    return [new Hint(
                        self::HINT_TYPE,
                        self::HINT_MESSAGE,
                        $file,
                        $node->lineno,
                        self::HINT_LINK
                    )];
                }
            }

            // `$a !== $b ? true : false` or `$a != $b ? true : false`
            if ($cond->flags === \ast\flags\BINARY_IS_NOT_IDENTICAL || $cond->flags === \ast\flags\BINARY_IS_NOT_EQUAL) {
                if ($this->isTrueNode($true) && $this->isFalseNode($false)) {
                    return [new Hint(
                        self::HINT_TYPE,
                        self::HINT_MESSAGE,
                        $file,
                        $node->lineno,
                        self::HINT_LINK
                    )];
                }
            }
        }

        return [];
    }

    /**
    * Check if it is a true expression
    *
    * @param mixed $node Target AST node or expression.
    * @return boolean Result.
    */
    private function isTrueNode($node): bool
    {
        return $node instanceof Node
                 && $node->kind === \ast\AST_CONST
                 && $node->children['name']->children['name'] === "true";
    }

    /**
    * Check if it is a false expression
    *
    * @param mixed $node Target AST node or expression.
    * @return boolean Result.
    */
    private function isFalseNode($node): bool
    {
        return $node instanceof Node
                 && $node->kind === \ast\AST_CONST
                 && $node->children['name']->children['name'] === "false";
    }
}
