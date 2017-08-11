<?php

namespace Pahout;

class Warning
{
    private $type;
    private $message;
    private $line;

    public function __construct(string $type, string $message, int $line)
    {
        $this->type = $type;
        $this->message = $message;
        $this->line = $line;
    }
}
