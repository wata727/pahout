<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\ArraySyntaxLong;
use Pahout\Hint;

class ArraySyntaxLongTest extends TestCase
{
    public function testArraySyntaxLong()
    {
        $root = \ast\parse_code('<?php array(1, 2, 3) ?>', 40);

        $tester = PahoutHelper::create(new ArraySyntaxLong());
        $tester->test($root);

        $this->assertCount(1, $tester->hints);
        $this->assertEquals('ArraySyntaxLong', $tester->hints[0]->type);
        $this->assertEquals('Use [...] syntax instead of array(...) syntax.', $tester->hints[0]->message);
        $this->assertEquals('./test.php', $tester->hints[0]->filename);
        $this->assertEquals(1, $tester->hints[0]->lineno);
    }

    public function testArraySyntaxShort()
    {
        $root = \ast\parse_code('<?php [1, 2, 3] ?>', 40);

        $tester = PahoutHelper::create(new ArraySyntaxLong());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
