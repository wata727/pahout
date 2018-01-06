<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect catch of different exception in the same implementation.
*
* In PHP 7.1 and later, a catch block may specify multiple exceptions using the pipe (|) character.
* This is useful for when different exceptions from different class hierarchies are handled the same.
*
* ## Before
*
* ```
* <?php
* try {
*     hoge();
* } catch (A $exn) {
*     echo "catch!";
*     fuga();
* } catch (B $exn) {
*     echo "catch!";
* } catch (C $exn) {
*     echo "catch!";
* }
* ```
*
* ## After
*
* ```
* <?php
* try {
*     hoge();
* } catch (A $exn) {
*     echo "catch!";
*     fuga();
* } catch (B | C $exn) {
*     echo "catch!";
* }
* ```
*/
class MultipleCatch implements Base
{
    use Howdah;

    /** Analyze catch block list (AST_CATCH_LIST) */
    const ENTRY_POINT = \ast\AST_CATCH_LIST;
    /** PHP version to enable this tool */
    const PHP_VERSION = '7.1.0';
    const HINT_TYPE = "MultipleCatch";
    const HINT_MESSAGE = 'Specifying %s in this catch block avoids redundant catch blocks.';
    const HINT_LINK = Hint::DOCUMENT_LINK."/MultipleCatch.md";

    /**
    * Detect catch blocks of the same implementation.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $hints = [];
        $catch_block_list = [];

        foreach ($node->children as $catch) {
            $klasses = $catch->children['class'];
            $stmts = $catch->children['stmts'];
            foreach ($catch_block_list as $catch_block) {
                $catch_klasses = $catch_block->children['class'];
                $catch_stmts = $catch_block->children['stmts'];
                if ($this->isEqualsWithoutLineno($catch_stmts, $stmts)) {
                    $hints[] = new Hint(
                        self::HINT_TYPE,
                        $this->messages(array_merge($catch_klasses->children, $klasses->children)),
                        $file,
                        $catch_stmts->lineno,
                        self::HINT_LINK
                    );
                }
            }
            $catch_block_list[] = $catch;
        }

        return $hints;
    }

    /**
    * Build a hint message from exceptions.
    *
    * @param Node[] $exceptions List of exceptions to specify.
    * @return string Hint message.
    */
    private function messages(array $exceptions)
    {
        $names = array_map(function ($exception) {
            return $exception->children['name'];
        }, $exceptions);

        if (count($names) > 1) {
            $last = array_slice($names, -1);
            $message = implode("`, `", array_slice($names, 0, count($names) - 1));
            $message = "`$message` and `$last[0]`";
        } else {
            $message = "`$names[0]`";
        }

        return sprintf(self::HINT_MESSAGE, $message);
    }
}
