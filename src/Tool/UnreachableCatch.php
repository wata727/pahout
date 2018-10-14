<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Hint;

/**
* Detect unreachable catch expressions.
*
* For example, Exception is the base class for all user exceptions, so if you catch it, it will not reach any further catch expressions.
*
* ## Before
*
* ```
* <?php
* class ApplicationError extends Exception {}
*
* try {
*     something();
* } catch (Exception $e) {
*     echo "general error";
* } catch (ApplicationError $e) {
*     echo "application error";
* }
* ```
*
* ## After
*
* ```
* <?php
* class ApplicationError extends Exception {}
*
* try {
*     something();
* } catch (ApplicationError $e) {
*     echo "application error";
* } catch (Exception $e) {
*     echo "general error";
* }
* ```
*
*/
class UnreachableCatch implements Base
{
    /** Analyze catch block list (AST_CATCH_LIST) */
    public const ENTRY_POINT = \ast\AST_CATCH_LIST;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '0.0.0';
    public const HINT_TYPE = "UnreachableCatch";
    private const HINT_MESSAGE = 'This exception handling will not be reached.';
    private const HINT_LINK = Hint::DOCUMENT_LINK."/UnreachableCatch.md";

    /**
    * Detect a catch expression not reachable by catch expressions of `Exception` or `Throwable`
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        while (count($node->children) >= 2) {
            $catch = array_shift($node->children);

            // If the class specification includes `Throwable`, doesn't allow any catch expressions after that.
            if ($this->isIncludeThrowableNode($catch)) {
                foreach ($node->children as $after_catch) {
                    if (!$this->isIncludeThrowableNode($after_catch)) {
                        return [new Hint(
                            self::HINT_TYPE,
                            self::HINT_MESSAGE,
                            $file,
                            $after_catch->lineno,
                            self::HINT_LINK
                        )];
                    }
                }
            }

            // If the class specification includes `Exception`, doesn't allow catch expressions other than `Throwable` after that.
            if ($this->isIncludeExceptionNode($catch)) {
                foreach ($node->children as $after_catch) {
                    if (!$this->isIncludeExceptionNode($after_catch)
                          && !$this->isIncludeThrowableNode($after_catch)) {
                        return [new Hint(
                            self::HINT_TYPE,
                            self::HINT_MESSAGE,
                            $file,
                            $after_catch->lineno,
                            self::HINT_LINK
                        )];
                    }
                }
            }
        }

        return [];
    }

    /**
    * Check if the node catches `Exception`
    *
    * @param Node $node The node to check.
    * @return boolean Result.
    */
    private function isIncludeExceptionNode(Node $node): bool
    {
        foreach ($node->children['class']->children as $class) {
            if ($class->children['name'] === 'Exception') {
                return true;
            }
        }

        return false;
    }

    /**
    * Check if the node catches `Throwable`
    *
    * @param Node $node The node to check.
    * @return boolean Result.
    */
    private function isIncludeThrowableNode(Node $node): bool
    {
        foreach ($node->children['class']->children as $class) {
            if ($class->children['name'] === 'Throwable') {
                return true;
            }
        }

        return false;
    }
}
