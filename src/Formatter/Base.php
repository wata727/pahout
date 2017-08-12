<?php

namespace Pahout\Formatter;

use Symfony\Component\Console\Output\OutputInterface;

abstract class Base
{
    protected $output;
    protected $files = [];
    protected $hints = [];

    public function __construct(OutputInterface $output, array $files, array $hints)
    {
        $this->output = $output;
        $this->files = $files;
        $this->hints = $hints;
    }

    abstract public function print();
}
