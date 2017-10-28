<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect duplicate key in array.
*
* If the keys of the array duplicate, it will be overwritten with the specified value later.
*
* ## Before
*
* ```
* <?php
* $array = ["a" => 1, "a" => 2];
* ```
*
* ## After
*
* ```
* <?php
* $array = ["a" => 1, "b" => 2];
* ```
*
*/
class DuplicateKey implements Base
{
    /** Analyze array declarations node (AST_ARRAY) */
    public const ENTRY_POINT = \ast\AST_ARRAY;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '0.0.0';
    public const HINT_TYPE = "DuplicateKey";
    private const HINT_MESSAGE = 'Duplicate keys found in array.';
    private const HINT_LINK = Hint::DOCUMENT_LINK."/DuplicateKey.md";

    /**
    * Detect duplicate numbers, strings, node key
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $hints = [];
        $keys = [];
        $elems = [];

        foreach ($node->children as $elem) {
            if ($elem instanceof Node && !is_null($elem->children['key'])) {
                $elems[] = $elem;
                $key = $elem->children['key'];
                $keys[] = $key instanceof Node ? $key->children : $key;
            }
        }

        while (count($keys) > 0) {
            $elem = array_pop($elems);
            $key = array_pop($keys);

            if ($key instanceof Node) {
                // If the key is a node, use equal comparison operator
                $strict = false;
            } else {
                // If the key is not a node, use identical comparison operator
                $strict = true;
            }

            if (in_array($key, $keys, $strict)) {
                $hints[] = new Hint(
                    self::HINT_TYPE,
                    self::HINT_MESSAGE,
                    $file,
                    $elem->lineno,
                    self::HINT_LINK
                );
            }
        }

        return $hints;
    }
}
