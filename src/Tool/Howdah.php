<?php declare(strict_types=1);

namespace Pahout\Tool;

use \ast\Node;

trait Howdah
{
    /**
    * Verify that it is the same except for line number of Node.
    *
    * @param mixed $a Object to compare.
    * @param mixed $b Object to compare.
    * @return boolean Result.
    */
    public function isEqualsWithoutLineno($a, $b): Bool
    {
        if (is_array($a)) {
            if (!is_array($b)) {
                return false;
            }
            foreach ($a as $key => $value) {
                if (!(array_key_exists($key, $b) && $this->isEqualsWithoutLineno($value, $b[$key]))) {
                    return false;
                }
            }
        } elseif ($a instanceof Node) {
            if (!$b instanceof Node) {
                return false;
            }
            if ($a->kind !== $b->kind || $a->flags !== $b->flags) {
                return false;
            }
            return $this->isEqualsWithoutLineno($a->children, $b->children);
        } elseif ($a !== $b) {
            return false;
        }

        return true;
    }
}
