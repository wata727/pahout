<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;
use Pahout\Hint;

interface Base
{
    public function run(string $file, Node $node): ?Hint;
}
