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
    /** Analyze calling class constants (AST_CATCH_LIST) */
    public const ENTRY_POINT = \ast\AST_CATCH_LIST;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '7.1.0';
    public const HINT_TYPE = "MultipleCatch";
    private const HINT_MESSAGE = 'A catch block may specify multiple exceptions.';

    /**
    * Detect catch blocks of the same implementation.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint|null A Hint obtained from results. If it does not exist, it returns null.
    */
    public function run(string $file, Node $node): ?Hint
    {
        $catch_stmts_list = [];
        foreach ($node->children as $catch) {
            $stmts = $catch->children['stmts'];
            foreach ($catch_stmts_list as $catch_stmts) {
                if ($this->isEqualsWithoutLineno($catch_stmts, $stmts)) {
                    return new Hint(
                        self::HINT_TYPE,
                        self::HINT_MESSAGE,
                        $file,
                        $catch_stmts->lineno
                    );
                }
            }
            $catch_stmts_list[] = $stmts;
        }

        return null;
    }

    /**
    * Verify that it is the same except for line number of Node.
    *
    * @param mixed $a Object to compare.
    * @param mixed $b Object to compare.
    * @return boolean Result.
    */
    public function isEqualsWithoutLineno($a, $b): Bool
    {
        if (is_array($a)) {
            if (!is_array($b)) {
                return false;
            }
            foreach ($a as $key => $value) {
                if (!(array_key_exists($key, $b) && $this->isEqualsWithoutLineno($value, $b[$key]))) {
                    return false;
                }
            }
        } elseif ($a instanceof Node) {
            if (!$b instanceof Node) {
                return false;
            }
            if ($a->kind !== $b->kind || $a->flags !== $b->flags) {
                return false;
            }
            return $this->isEqualsWithoutLineno($a->children, $b->children);
        } elseif ($a !== $b) {
            return false;
        }

        return true;
    }
}
