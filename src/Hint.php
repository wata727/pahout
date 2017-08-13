<?php

namespace Pahout;

class Hint
{
    public $type;
    public $message;
    public $filename;
    public $lineno;

    public function __construct(string $type, string $message, string $filename, int $lineno)
    {
        $this->type = $type;
        $this->message = $message;
        $this->filename = $filename;
        $this->lineno = $lineno;
    }
}
