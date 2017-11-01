<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect case condition in switch statement.
*
* There is no need to write the same condition in the switch statement. It is unintended behavior or redundant.
*
* ## Before
*
* ```
* <?php
* switch ($a) {
*     case 1:
*         echo "hoge";
*         break;
*     case 1:
*         echo "fuga";
*         break;
*     default:
*         echo "meow";
*         break;
* }
* ```
*
* ## After
*
* ```
* <?php
* switch ($a) {
*     case 1:
*         echo "hoge";
*         break;
*     case 2:
*         echo "fuga";
*         break;
*     default:
*         echo "meow";
*         break;
* }
* ```
*
*/
class DuplicateCaseCondition implements Base
{
    use Howdah;

    /** Analyze switch statement node (AST_SWITCH_LIST) */
    public const ENTRY_POINT = \ast\AST_SWITCH_LIST;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '0.0.0';
    public const HINT_TYPE = "DuplicateCaseCondition";
    private const HINT_MESSAGE = 'Duplicate case condition found.';
    private const HINT_LINK = Hint::DOCUMENT_LINK."/DuplicateCaseCondition.md";

    /**
    * Detect case condition in switch statement.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $hints = [];
        $case_conds = [];

        foreach ($node->children as $case) {
            $cond = $case->children['cond'];
            foreach ($case_conds as $case_cond) {
                if ($this->isEqualsWithoutLineno($case_cond, $cond)) {
                    $hints[] = new Hint(
                        self::HINT_TYPE,
                        self::HINT_MESSAGE,
                        $file,
                        $case->lineno,
                        self::HINT_LINK
                    );
                }
            }
            $case_conds[] = $cond;
        }

        return $hints;
    }
}
