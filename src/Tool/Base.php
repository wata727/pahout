<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Hint;

interface Base
{
    /**
    * Receive and analyze AST node and file name.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return Hint|null A Hint obtained from results. If it does not exist, it returns null.
    */
    public function run(string $file, Node $node): ?Hint;
}
