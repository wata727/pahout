<?php declare(strict_types=1);

namespace Pahout\Annotation;

use Pahout\Hint;
use Pahout\Logger;

/**
* Rebel annocation
*
* For example, When there is a comment in a format like
* `@rebel ToolName`, Pahout ignores the tool's hint.
*/
class Rebel extends Base
{
    /**
    * Annotation factory
    *
    * If the comment contains `@rebel` annotation,
    * this method creates an annotation instance.
    *
    * @param string  $comment    A body of comment.
    * @param integer $start_line The number of comment's start line.
    * @param string  $filename   The file name including the comment.
    * @return null|Rebel If the comment contains rebel annotation, its instance, otherwise null.
    */
    public static function create(string $comment, int $start_line, string $filename)
    {
        if (preg_match('/@rebel\s+([^\s]+)/', $comment, $match) === 1) {
            $body = $match[1];
            $end_line = $start_line + substr_count($comment, PHP_EOL);

            Logger::getInstance()->debug("Rebel annotation found: body=$body start=$start_line end=$end_line");
            return new Rebel($body, $start_line, $end_line, $filename);
        } else {
            return null;
        }
    }

    /**
    * Determine whether hint is target of annotation
    *
    * It checks file name, hint type, and the range of comments.
    *
    * @param Hint $hint An instance of Hint.
    * @return boolean If the hint is affected by this annotion, true, otherwise false.
    */
    public function isAffected(Hint $hint): bool
    {
        return $hint->filename === $this->filename
            && $hint->type === $this->body
            && ($this->start_line - 1) <= $hint->lineno
            && $hint->lineno <= ($this->end_line + 1);
    }
}
