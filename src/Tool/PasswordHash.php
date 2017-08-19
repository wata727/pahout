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
    /** Analyze function call (AST_CALL) */
    public const ENTRY_POINT = \ast\AST_CALL;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '5.5.0';
    public const HINT_TYPE = "PasswordHash";
    private const HINT_MESSAGE = "Use of `password_hash()` is encouraged.";

    /**
    * Detect calls to `crypt()` function.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $expr = $node->children['expr'];

        if ($expr->kind === \ast\AST_NAME) {
            if ($expr->children['name'] === 'crypt') {
                return [new Hint(
                    self::HINT_TYPE,
                    self::HINT_MESSAGE,
                    $file,
                    $node->lineno
                )];
            }
        }

        return [];
    }
}
