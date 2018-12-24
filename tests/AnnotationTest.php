<?php declare(strict_types=1);

namespace Pahout\Test;

use PHPUnit\Framework\TestCase;
use Pahout\Annotation;
use Pahout\Annotation\Rebel;
use Pahout\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;

class AnnotationTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
    }

    public function test_create()
    {
        $code = <<<'CODE'
<?php

/** @rebel SingleLineDocComment */
$str = file_get_contents('.php-version');
/**
* This is multiple document comment.
*
* @suppress PhanComment
* @rebel MultipleDocComment
*
* This is multiple document comment.
*/
echo $str; # @rebel SingleLineComment
CODE;
        $annotates = Annotation::create('./test.php', $code);
        $this->assertEquals($annotates, [
            new Rebel("SingleLineDocComment", 3, 3, './test.php'),
            new Rebel("MultipleDocComment", 5, 12, './test.php'),
            new Rebel("SingleLineComment", 13, 13, './test.php')
        ]);
    }
}
