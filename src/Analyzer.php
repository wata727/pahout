<?php

namespace Pahout;

use Pahout\Analyzer\LongArray;

class Analyzer
{
    public $warnings = [];
    private $files = [];
    private $analyzers = [];

    public function __construct(array $files)
    {
        if (count($files) === 0) {
            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator('.')),
                '/^.+\.php$/i'
            );

            foreach ($iterator as $file) {
                $this->files[] = $file->getPathname();
            }
        } else {
            $this->files = $files;
        }

        $this->analyzers = [
            new LongArray()
        ];
    }

    public function run()
    {
        foreach ($this->files as $file) {
            $root = \ast\parse_file($file, 40);
            $this->traverse($root);
        }

        $this->warnings = array_filter($this->warnings, function ($warning) {
            return !is_null($warning);
        });
    }

    private function traverse(\ast\Node $node)
    {
        foreach ($this->analyzers as $analyzer) {
            if (get_class($analyzer)::ENTRY_POINT === $node->kind) {
                $this->warnings[] = $analyzer->run($node);
            }
        }

        foreach ($node->children as $type => $child) {
            if ($child instanceof \ast\Node) {
                $this->traverse($child);
            }
        }
    }
}
