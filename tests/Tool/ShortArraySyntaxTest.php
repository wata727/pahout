<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\ShortArraySyntax;
use Pahout\Hint;
use Pahout\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;

class ShortArraySyntaxTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_array_syntax_long()
    {
        $root = \ast\parse_code('<?php array(1, 2, 3) ?>', 40);

        $tester = PahoutHelper::create(new ShortArraySyntax());
        $tester->test($root);

        $this->assertCount(1, $tester->hints);
        $this->assertEquals('ShortArraySyntax', $tester->hints[0]->type);
        $this->assertEquals('Use [...] syntax instead of array(...) syntax.', $tester->hints[0]->message);
        $this->assertEquals('./test.php', $tester->hints[0]->filename);
        $this->assertEquals(1, $tester->hints[0]->lineno);
    }

    public function test_array_syntax_short()
    {
        $root = \ast\parse_code('<?php [1, 2, 3] ?>', 40);

        $tester = PahoutHelper::create(new ShortArraySyntax());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
