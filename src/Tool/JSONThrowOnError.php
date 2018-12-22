<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Hint;

/**
* Check whether a `json_*` function has `JSON_THROW_ON_ERROR`
*
* PHP 7.3 makes it to easy to handle `json_*` function errors easily.
* Previously, these function didn't throw an exception when an error occurred. But now, it throws `JsonException` if passed `JSON_THROW_ON_ERROR` as a option. This is a better way to handle errors.
*
* ## Before
*
* ```php
* json_decode("{");
* if (json_last_error() !== JSON_ERROR_NONE) {
*     echo "An error occurred";
* }
* ```
*
* ## After
*
* ```php
* try {
*     json_decode("{", false, 512, JSON_THROW_ON_ERROR);
* } catch (JsonException $exn) {
*     echo "An error occurred";
* }
* ```
*/
class JSONThrowOnError implements Base
{
    use Howdah;

    public const ENTRY_POINT = \ast\AST_CALL;
    public const PHP_VERSION = '7.3.0';
    public const HINT_TYPE = "JSONThrowOnError";
    private const HINT_MESSAGE = 'Encourage to specify JSON_THROW_ON_ERROR option.';
    private const HINT_LINK = Hint::DOCUMENT_LINK."/JSONThrowOnError.md";

    /**
    * Check whether a `json_*` function has `JSON_THROW_ON_ERROR`
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        if ($this->isFunctionCall($node, 'json_decode')) {
            $options = $node->children['args']->children[3] ?? null;
            if ($this->shouldCheckOption($options) && !$this->isIncludeJSONThrowOnErrorOption($options)) {
                return [new Hint(
                    self::HINT_TYPE,
                    self::HINT_MESSAGE,
                    $file,
                    $node->lineno,
                    self::HINT_LINK
                )];
            }
        }
        if ($this->isFunctionCall($node, 'json_encode')) {
            $options = $node->children['args']->children[1] ?? null;
            if ($this->shouldCheckOption($options) && !$this->isIncludeJSONThrowOnErrorOption($options)) {
                return [new Hint(
                    self::HINT_TYPE,
                    self::HINT_MESSAGE,
                    $file,
                    $node->lineno,
                    self::HINT_LINK
                )];
            }
        }

        return [];
    }

    /**
    * Check whether the passed options node should be checked.
    *
    * This function is used to suppress false positives.
    *
    * @param mixed $node Options node.
    * @return boolean Result.
    */
    private function shouldCheckOption($node): Bool
    {
        if (!$node instanceof Node) {
            return true;
        }
        if ($node->kind === \ast\AST_CONST) {
            return true;
        }
        if ($node->kind === \ast\AST_BINARY_OP && $node->flags === \ast\flags\BINARY_BITWISE_OR) {
            return true;
        }

        return false;
    }

    /**
    * Check whether the passed node has `JSON_THROW_ON_ERROR`
    *
    * This function is also aware of inclusive or.
    *
    * @param mixed $node Node or others.
    * @return boolean Result.
    */
    private function isIncludeJSONThrowOnErrorOption($node): Bool
    {
        if (!$node instanceof Node) {
            return false;
        }

        if ($node->kind === \ast\AST_CONST) {
            $name = $node->children["name"];
            if ($name->kind === \ast\AST_NAME && $name->children["name"] === "JSON_THROW_ON_ERROR") {
                return true;
            }
        }

        if ($node->kind === \ast\AST_BINARY_OP && $node->flags === \ast\flags\BINARY_BITWISE_OR) {
            return $this->isIncludeJSONThrowOnErrorOption($node->children["left"])
                     || $this->isIncludeJSONThrowOnErrorOption($node->children["right"]);
        }

        return false;
    }
}
