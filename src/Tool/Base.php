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
    * @return Hint[] List of hints obtained from results.
    */
    public function run(string $file, Node $node): array;
}
