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
    use Howdah;

    /** Analyze function call declarations (AST_CALL) */
    const ENTRY_POINT = \ast\AST_CALL;
    /** PHP version to enable this tool */
    const PHP_VERSION = '4.0.3';
    const HINT_TYPE = "EscapeShellArg";
    const HINT_MESSAGE = "This function allows attackers to pass arbitrary number of arguments.";
    const HINT_LINK = Hint::DOCUMENT_LINK."/EscapeShellArg.md";

    /**
    * Detect escapeshellcmd() function call.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        if ($this->isFunctionCall($node, 'escapeshellcmd')) {
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
