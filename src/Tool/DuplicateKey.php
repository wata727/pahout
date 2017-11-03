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
    use Howdah;

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

        foreach ($node->children as $elem) {
            $key = $elem->children['key'];
            // If the array does not have key, ignores this element.
            if (!is_null($key)) {
                foreach ($keys as $other_key) {
                    if ($this->isEqualsWithoutLineno($key, $other_key)) {
                        $hints[] = new Hint(
                            self::HINT_TYPE,
                            self::HINT_MESSAGE,
                            $file,
                            $elem->lineno,
                            self::HINT_LINK
                        );
                    }
                }
                $keys[] = $key;
            }
        }

        return $hints;
    }
}
