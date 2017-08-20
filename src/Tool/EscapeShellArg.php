<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect calls of dangerous escape function.
*
* There is a specification that `escapeshellcmd()` does not escape when single quotes or double quotes are paired.
* This behavior allows the attackers to pass arbitrary number of arguments. You must apply `escapeshellarg()` for each argument instead.
*
* ## Before
*
* ```
* <?php
* $filename = 'test.php" "/etc/passwd';
* $cmd = "ls \"$filename\"";
* $cmd = escapeshellcmd($cmd);
* system($cmd);
* ```
*
* ## After
*
* ```
* <?php
* $filename = 'test.php" "/etc/passwd';
* $filename = escapeshellarg($filename);
* $cmd = "ls $filename";
* system($cmd);
* ```
*/
class EscapeShellArg implements Base
{
    /** Analyze function call declarations (AST_CALL) */
    public const ENTRY_POINT = \ast\AST_CALL;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '4.0.3';
    public const HINT_TYPE = "EscapeShellArg";
    private const HINT_MESSAGE = "This function allows the attacker to pass arbitrary number of arguments.";
    private const HINT_LINK = Hint::DOCUMENT_LINK."/EscapeShellArg.md";

    /**
    * Detect escapeshellcmd() function call.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $expr = $node->children['expr'];

        if ($expr->kind === \ast\AST_NAME) {
            if ($expr->children['name'] === 'escapeshellcmd') {
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
