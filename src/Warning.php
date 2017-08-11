<?php

namespace Pahout;

class Warning
{
    private $type;
    private $message;
    private $filename;
    private $line;

    public function __construct(string $type, string $message, string $filename, int $line)
    {
        $this->type = $type;
        $this->message = $message;
        $this->filename = $filename;
        $this->line = $line;
    }
}
