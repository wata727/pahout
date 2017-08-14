<?php declare(strict_types=1);

namespace Pahout;

/**
* Hints for better writing PHP
*
* Pahout will generate a hint if the tool finds the problem code.
*/
class Hint
{
    /** @var string Types of hints to identify tool */
    public $type;

    /** @var string A message of the hint to be displayed */
    public $message;

    /** @var string The file name of indicated by hint */
    public $filename;

    /** @var integer Number of lines of file indicated by hint */
    public $lineno;

    /**
    * Hint constructor
    *
    * @param string  $type     Types of hints to identify tool.
    * @param string  $message  A message of the hint to be displayed.
    * @param string  $filename The file name of indicated by hint.
    * @param integer $lineno   Number of lines of file indicated by hint.
    */
    public function __construct(string $type, string $message, string $filename, int $lineno)
    {
        $this->type = $type;
        $this->message = $message;
        $this->filename = $filename;
        $this->lineno = $lineno;
    }
}
