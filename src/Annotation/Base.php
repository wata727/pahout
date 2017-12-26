<?php declare(strict_types=1);

namespace Pahout\Annotation;

use Pahout\Hint;

/**
* Annotation abstract class
*
* Annotation must have a range of comments and a body.
* It also has a method to determine the influence range
* calculated from them.
*/
abstract class Base
{
    /** @var string A body of annotation */
    protected $body;
    /** @var integer The number of start line in the comment including annotation */
    protected $start_line;
    /** @var integer The number of end line in the comment including annotation */
    protected $end_line;
    /** @var string A file name */
    protected $filename;

    /**
    * Annotation constructor
    *
    * @param string  $body       A body of annotation.
    * @param integer $start_line The number of start line in the comment including annotation.
    * @param integer $end_line   The number of end line in the comment including annotation.
    * @param string  $filename   A file name.
    */
    public function __construct(string $body, int $start_line, int $end_line, string $filename)
    {
        $this->body = $body;
        $this->start_line = $start_line;
        $this->end_line = $end_line;
        $this->filename = $filename;
    }

    /**
    * Determine whether hint is target of annotation
    *
    * @param Hint $hint An instance of Hint.
    * @return boolean If the hint is affected by this annotion, true, otherwise false.
    */
    abstract public function isAffected(Hint $hint): bool;
}
