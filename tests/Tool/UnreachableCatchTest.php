<?php

namespace Pahout\Test\Tool;

use PHPUnit\Framework\TestCase;
use Pahout\Test\helper\PahoutHelper;
use Pahout\Tool\UnreachableCatch;
use Pahout\Hint;
use Pahout\Logger;
use Pahout\Config;
use Symfony\Component\Console\Output\ConsoleOutput;

class UnreachableCatchTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_unreachable_catch()
    {
        $code = <<<'CODE'
<?php

try {
    throw new ApplicationError("An error ocurred");
} catch (CoreError $e) {
    echo "¯\_(ツ)_/¯";
} catch (Exception $e) {
    echo "Unexpected Error";
} catch (ApplicationError $e) {
    recover();
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnreachableCatch());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'UnreachableCatch',
                    'This exception handling will not be reached.',
                    './test.php',
                    9,
                    Hint::DOCUMENT_LINK.'/UnreachableCatch.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_reachable_catch()
    {
        $code = <<<'CODE'
<?php

try {
    throw new ApplicationError("An error ocurred");
} catch (CoreError $e) {
    echo "¯\_(ツ)_/¯";
} catch (ApplicationError $e) {
    recover();
} catch (Exception $e) {
    echo "Unexpected Error";
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnreachableCatch());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_unreachable_catch_with_throwable()
    {
        $code = <<<'CODE'
<?php

try {
    throw new ApplicationError("An error ocurred");
} catch (Throwable $e) {
    echo "¯\_(ツ)_/¯";
} catch (Exception $e) {
    echo "Unexpected Error";
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnreachableCatch());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'UnreachableCatch',
                    'This exception handling will not be reached.',
                    './test.php',
                    7,
                    Hint::DOCUMENT_LINK.'/UnreachableCatch.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_reachable_catch_with_throwable()
    {
        $code = <<<'CODE'
<?php

try {
    throw new ApplicationError("An error ocurred");
} catch (Exception $e) {
    echo "Unexpected Error";
} catch (Throwable $e) {
    echo "¯\_(ツ)_/¯";
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnreachableCatch());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }

    public function test_unreachable_catch_with_multiple_exception()
    {
        $code = <<<'CODE'
<?php

try {
    throw new ApplicationError("An error ocurred");
} catch (CoreError|Throwable $e) {
    echo "¯\_(ツ)_/¯";
} catch (Exception $e) {
    echo "Unexpected Error";
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnreachableCatch());
        $tester->test($root);

        $this->assertEquals(
            [
                new Hint(
                    'UnreachableCatch',
                    'This exception handling will not be reached.',
                    './test.php',
                    7,
                    Hint::DOCUMENT_LINK.'/UnreachableCatch.md'
                )
            ],
            $tester->hints
        );
    }

    public function test_reachable_catch_with_multiple_exception()
    {
        $code = <<<'CODE'
<?php

try {
    throw new ApplicationError("An error ocurred");
} catch (CoreError $e) {
    echo "¯\_(ツ)_/¯";
} catch (Exception|Throwable $e) {
    echo "Unexpected Error";
}
CODE;
        $root = \ast\parse_code($code, Config::AST_VERSION);

        $tester = PahoutHelper::create(new UnreachableCatch());
        $tester->test($root);

        $this->assertEmpty($tester->hints);
    }
}
