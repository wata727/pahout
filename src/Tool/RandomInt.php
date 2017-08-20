<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect calls of cryptographically insecure functions.
*
* `random_int()` and `random_byte()` added in PHP 7.0 generate cryptographically secure pseudo-random integers.
* `rand()` and `mt_rand()` are cryptographically insecure.
*
* ## Before
*
* ```
* <?php
* $rand = mt_rand();
* ```
*
* ## After
*
* ```
* <?php
* $rand = random_int(0, PHP_INT_MAX);
* ```
*/
class RandomInt implements Base
{
    /** Analyze function call declarations (AST_CALL) */
    public const ENTRY_POINT = \ast\AST_CALL;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '7.0.0';
    public const HINT_TYPE = "RandomInt";
    private const HINT_MESSAGE = "This function is not cryptographically secure. Consider using `random_int()`, `random_bytes()`, or `openssl_random_pseudo_bytes()` instead.";
    private const HINT_LINK = Hint::DOCUMENT_LINK."/RandomInt.md";
    private const FUNCTION_LIST = ['rand', 'mt_rand'];

    /**
    * Detect rand() and mt_rand() function call.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $expr = $node->children['expr'];

        if ($expr->kind === \ast\AST_NAME) {
            if (in_array($expr->children['name'], self::FUNCTION_LIST, true)) {
                return [new Hint(
                    self::HINT_TYPE,
                    self::HINT_MESSAGE,
                    $file,
                    $node->lineno,
                    self::HINT_LINK
                )];
            } else {
                Logger::getInstance()->debug('Ignore function name: '.$expr->children['name']);
            }
        } else {
            Logger::getInstance()->debug('Ignore AST kind: '.$expr->kind);
        }

        return [];
    }
}
