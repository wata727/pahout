<?php declare(strict_types=1);

namespace Pahout;

use Pahout\Annotation\Base;
use Pahout\Annotation\Rebel;

/**
* Annotation factory
*
* Get the comment token from the source code
* and generate the appropriate annotation instance.
*/
class Annotation
{
    /**
    * Annotation facotry method
    *
    * Get tokens from the source code by `token_get_all`
    * and return an array of the obtained annotation instance.
    *
    * @param string $filename A file name.
    * @param string $source   A body of file.
    * @return Base[] List of annotation instance.
    */
    public static function create(string $filename, string $source): array
    {
        $annotations = [];

        $tokens = token_get_all($source);
        $comments = array_filter($tokens, function ($token) {
            return in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true);
        });

        foreach ($comments as $comment) {
            $rebel = Rebel::create($comment[1], $comment[2], $filename);
            if ($rebel) {
                $annotations[] = $rebel;
            }
        }

        return $annotations;
    }
}
