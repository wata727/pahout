<?php

namespace Pahout\Analyzer;

use Pahout\Warning;

interface Base
{
    public function run(\ast\Node $node): ?Warning;
}
