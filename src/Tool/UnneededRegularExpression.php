<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Find `preg_match()` which does not need regular expressions.
*
* Under the following conditions, using `strpos()` instead of `preg_match()` will be faster.
*
* 1. Not using `matches` variables.
* 2. Not using a pattern including pattern modifiers.
* 3. Not using a pattern including meta characters.
*
*/
class UnneededRegularExpression implements Base
{
    use Howdah;

    const ENTRY_POINT = \ast\AST_CALL;
    const PHP_VERSION = '0.0.0';
    const HINT_TYPE = "UnneededRegularExpression";
    const HINT_MESSAGE = 'Using `strpos()` instead of `preg_match()` will be faster.';
    const HINT_LINK = Hint::DOCUMENT_LINK."/UnneededRegularExpression.md";

    /**
    * Find `preg_match()` which does not need regular expressions.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        if ($this->isFunctionCall($node, 'preg_match')) {
            $args = $node->children['args']->children;
            if (count($args) > 2) {
                return [];
            }

            if (is_string($args[0]) && $this->isUnneededRegExp($args[0])) {
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

    /**
    * Check whether it is a regular expression pattern that can use `strpos()`.
    *
    * The following pattern returns false:
    *
    * 1. Including pattern modifiers.
    * 2. Including meta characters.
    *
    * @param string $str A regular expression pattern for testing.
    * @return boolean The result of testing.
    */
    private function isUnneededRegExp(string $str): Bool
    {
        if (substr($str, -1) !== "/") {
            return false;
        }

        return $str === preg_quote($str);
    }
}
