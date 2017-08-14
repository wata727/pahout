<?php declare(strict_types=1);

namespace Pahout\Exception;

/**
* Exception when invalid value is specified in config option.
*
* For example, it will be thrown if you specify a nonexistent formatter.
*/
class InvalidConfigOptionValueException extends \Exception
{
}
