<?php

namespace Pahout\Analyzer;

use Pahout\Warning;

interface Base
{
    public function run(string $file, \ast\Node $node): ?Warning;
}
