<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Logger;
use Pahout\Hint;

/**
* Detect access to class constants using `get_class`.
*
* As of PHP 5.3.0, it's possible to reference the class using a variable.
* Class constants are defined for classes, but they can also be referenced from instances. Therefore, it is not necessary to get the class name from the instance.
*
* ## Before
*
* ```
* <?php
* $instance = new Hoge();
* get_class($instance)::CONSTANT;
* ```
*
* ## After
*
* ```
* <?php
* $instance = new Hoge();
* $instance::CONSTANT;
* ```
*/
class InstanceConstant implements Base
{
    /** Analyze calling class constants (AST_CLASS_CONST) */
    public const ENTRY_POINT = \ast\AST_CLASS_CONST;
    /** PHP version to enable this tool */
    public const PHP_VERSION = '5.3.0';
    public const HINT_TYPE = "InstanceConstant";
    private const HINT_MESSAGE = 'You can access class constants from instances.';

    /**
    * Detects reference to class constants with call to `get_class` function
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array
    {
        $klass = $node->children['class'];

        if ($klass->kind === \ast\AST_CALL) {
            $expr = $klass->children['expr'];
            if ($expr->kind === \ast\AST_NAME) {
                if ($expr->children['name'] === 'get_class') {
                    return [new Hint(
                        self::HINT_TYPE,
                        self::HINT_MESSAGE,
                        $file,
                        $klass->lineno
                    )];
                } else {
                    Logger::getInstance()->debug('Ignore function name: '.$expr->children['name']);
                }
            } else {
                Logger::getInstance()->debug('Ignore expr AST kind: '.$expr->kind);
            }
        } else {
            Logger::getInstance()->debug('Ignore klass AST kind: '.$klass->kind);
        }

        return [];
    }
}
