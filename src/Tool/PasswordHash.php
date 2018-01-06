<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect calls to `crypt()` function.
*
* The `password_hash()` function added in PHP 5.5 is a wrapper for `crypt()` function.
* Also, since it is compatible with existing password hashes, you should use this.
*
* ## Before
*
* ```
* <?php
* $password = crypt('secret text', generate_salt());
* ```
*
* ## After
*
* ```
* <?php
* $password = password_hash('secret text', PASSWORD_DEFAULT);
* ```
*/
class PasswordHash implements Base
{
    use Howdah;

    /** Analyze function call (AST_CALL) */
    const ENTRY_POINT = \ast\AST_CALL;
    /** PHP version to enable this tool */
    const PHP_VERSION = '5.5.0';
    const HINT_TYPE = "PasswordHash";
    const HINT_MESSAGE = "Use of `password_hash()` is encouraged.";
    const HINT_LINK = Hint::DOCUMENT_LINK."/PasswordHash.md";

    /**
    * Detect calls to `crypt()` function.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        if ($this->isFunctionCall($node, 'crypt')) {
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
