<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Hint;

/**
* Check whether the script declares `strict_types` directive
*
* Strict typing is introduced from PHP 7.0. Previously, PHP will coerce values into the expected scalar type even if the value type is wrong.
* By declaring `strict_types` you can enable strict mode.
*
* ## Before
*
* ```php
* <?php
*
* function sum(int $a, int $b) {
*     return $a + $b;
* }
*
* var_dump(sum(1, 2));
* var_dump(sum(1.5, 2.5));
* ```
*
* ## After
*
* ```php
* <?php declare(strict_types=1);
*
* function sum(int $a, int $b) {
*     return $a + $b;
* }
*
* var_dump(sum(1, 2));
* var_dump(sum(1.5, 2.5));
* ```
*
*/
class DeclareStrictTypes implements Base
{
    public const ENTRY_POINT = \ast\AST_STMT_LIST;
    public const PHP_VERSION = '7.0.0';
    public const HINT_TYPE = "DeclareStrictTypes";
    private const HINT_MESSAGE = 'Missing strict type declaration. The default types are not strict.';
    private const HINT_LINK = Hint::DOCUMENT_LINK."/DeclareStrictTypes.md";

    /** @var string Current inspection file. It is used only for top level inspection. */
    private $inspectedFile;

    /**
    * Check whether the passed node has `strict_types` declaration.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        // This inspection only works to top level statements in each file.
        if ($this->inspectedFile === $file) {
            return [];
        }

        $found = false;
        foreach ($node->children as $child) {
            if (!$child instanceof Node) {
                continue;
            }

            if ($child->kind !== \ast\AST_DECLARE) {
                continue;
            }

            $declares = $child->children["declares"];
            if ($declares->kind !== \ast\AST_CONST_DECL) {
                continue;
            }

            foreach ($declares->children as $declare) {
                if ($declare->kind === \ast\AST_CONST_ELEM && $declare->children["name"] === "strict_types") {
                    $found = true;
                }
            }
        }
        $this->inspectedFile = $file;

        if (!$found) {
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
}
