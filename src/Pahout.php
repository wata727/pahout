<?php

namespace Pahout;

use \ast\Node;
use Pahout\Tool\ArraySyntaxLong;

class Pahout
{
    public $hints = [];
    private $files = [];
    private $tools = [];

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

        $this->tools = [
            new ArraySyntaxLong()
        ];
    }

    public function instruct()
    {
        foreach ($this->files as $file) {
            $root = \ast\parse_file($file, 40);
            $this->traverse($file, $root);
        }
    }

    private function traverse(string $file, Node $node)
    {
        foreach ($this->tools as $tool) {
            if (get_class($tool)::ENTRY_POINT === $node->kind) {
                $hint = $tool->run($file, $node);
                if ($hint) {
                    $this->hints[] = $hint;
                }
            }
        }

        foreach ($node->children as $type => $child) {
            if ($child instanceof Node) {
                $this->traverse($file, $child);
            }
        }
    }
}
