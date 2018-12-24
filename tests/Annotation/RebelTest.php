<?php declare(strict_types=1);

namespace Pahout\Test\Annotation;

use PHPUnit\Framework\TestCase;
use Pahout\Annotation\Rebel;
use Pahout\Hint;
use Pahout\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;

class RebelAnnotationTest extends TestCase
{
    public function setUp()
    {
        Logger::getInstance(new ConsoleOutput());
        $this->rebel = new Rebel("MultipleCatch", 12, 15, './test.php');
    }

    public function test_line_is_affected()
    {
        $this->assertFalse($this->rebel->isAffected(new Hint(
            'MultipleCatch',
            'A catch block may specify multiple exceptions.',
            './test.php',
            10,
            Hint::DOCUMENT_LINK.'/MultipleCatch.md'
        )));

        $this->assertTrue($this->rebel->isAffected(new Hint(
            'MultipleCatch',
            'A catch block may specify multiple exceptions.',
            './test.php',
            11,
            Hint::DOCUMENT_LINK.'/MultipleCatch.md'
        )));

        $this->assertTrue($this->rebel->isAffected(new Hint(
            'MultipleCatch',
            'A catch block may specify multiple exceptions.',
            './test.php',
            13,
            Hint::DOCUMENT_LINK.'/MultipleCatch.md'
        )));

        $this->assertTrue($this->rebel->isAffected(new Hint(
            'MultipleCatch',
            'A catch block may specify multiple exceptions.',
            './test.php',
            16,
            Hint::DOCUMENT_LINK.'/MultipleCatch.md'
        )));

        $this->assertFalse($this->rebel->isAffected(new Hint(
            'MultipleCatch',
            'A catch block may specify multiple exceptions.',
            './test.php',
            17,
            Hint::DOCUMENT_LINK.'/MultipleCatch.md'
        )));
    }

    public function test_type_is_affected()
    {
        $this->assertFalse($this->rebel->isAffected(new Hint(
            'NullCoalescingOperator',
            'Use null coalecing operator instead of ternary operator.',
            './test.php',
            13,
            Hint::DOCUMENT_LINK.'/NullCoalescingOperator.md'
        )));

        $this->assertTrue($this->rebel->isAffected(new Hint(
            'MultipleCatch',
            'A catch block may specify multiple exceptions.',
            './test.php',
            13,
            Hint::DOCUMENT_LINK.'/MultipleCatch.md'
        )));
    }

    public function test_filename_is_affected()
    {
        $this->assertFalse($this->rebel->isAffected(new Hint(
            'MultipleCatch',
            'A catch block may specify multiple exceptions.',
            './vendor.php',
            13,
            Hint::DOCUMENT_LINK.'/MultipleCatch.md'
        )));

        $this->assertTrue($this->rebel->isAffected(new Hint(
            'MultipleCatch',
            'A catch block may specify multiple exceptions.',
            './test.php',
            13,
            Hint::DOCUMENT_LINK.'/MultipleCatch.md'
        )));
    }
}
