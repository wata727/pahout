<?php

namespace Pahout\Tool;

use Pahout\Hint;

interface Base
{
    public function run(string $file, \ast\Node $node): ?Hint;
}
