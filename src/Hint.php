<?php

namespace Pahout;

class Hint
{
    public $type;
    public $message;
    public $filename;
    public $line;

    public function __construct(string $type, string $message, string $filename, int $line)
    {
        $this->type = $type;
        $this->message = $message;
        $this->filename = $filename;
        $this->line = $line;
    }
}
