<?php

namespace Pahout;

use Pahout\Analyzer\ArraySyntaxLong;

class Analyzer
{
    public $hints = [];
    private $files = [];
    private $analyzers = [];

    public function __construct(array $files)
    {
        if (count($files) === 0) {
            $files = ['.'];
        }

        foreach ($files as $file) {
            if (is_dir($file)) {
                $iterator = new \RegexIterator(
                    new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($file)),
                    '/^.+\.php$/i'
                );

                foreach ($iterator as $file_obj) {
                    $this->files[] = $file_obj->getPathname();
                }
            } elseif (is_file($file)) {
                $this->files[] = $file;
            } else {
                # noop
            }
        }

        $this->analyzers = [
            new ArraySyntaxLong()
        ];
    }

    public function run()
    {
        foreach ($this->files as $file) {
            $root = \ast\parse_file($file, 40);
            $this->traverse($file, $root);
        }
    }

    private function traverse(string $file, \ast\Node $node)
    {
        foreach ($this->analyzers as $analyzer) {
            if (get_class($analyzer)::ENTRY_POINT === $node->kind) {
                $hint = $analyzer->run($file, $node);
                if ($hint) {
                    $this->hints[] = $hint;
                }
            }
        }

        foreach ($node->children as $type => $child) {
            if ($child instanceof \ast\Node) {
                $this->traverse($file, $child);
            }
        }
    }
}
