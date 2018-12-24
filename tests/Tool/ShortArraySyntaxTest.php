<?php declare(strict_types=1);

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\ShortArraySyntax;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class ShortArraySyntaxTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_array_syntax_long()
    {
        $root = \ast\parse_code('<?php array(1, 2, 3) ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new ShortArraySyntax());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'ShortArraySyntax',
                    'Use [...] syntax instead of array(...) syntax.',
                    './test.php',
                    1,
                    Hint::DOCUMENT_LINK.'/ShortArraySyntax.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_array_syntax_short()
    {
        $root = \ast\parse_code('<?php [1, 2, 3] ?>', Config::AST_VERSION);

        $tester = PahoutHelper::create(new ShortArraySyntax());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
