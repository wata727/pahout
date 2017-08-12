<?php

namespace Pahout\Analyzer;

use Pahout\Hint;

interface Base
{
    public function run(string $file, \ast\Node $node): ?Hint;
}
