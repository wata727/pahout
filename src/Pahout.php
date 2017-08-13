<?php

namespace Pahout;

use \ast\Node;
use Pahout\Logger;
use Pahout\Tool;

class Pahout
{
    public $hints = [];
    public $files = [];
    private $tools = [];

    public function __construct(array $files)
    {
        if (count($files) === 0) {
            Logger::getInstance()->info('Set target to the current directory');
            $files = ['.'];
        }

        foreach ($files as $file) {
            Logger::getInstance()->info('Load: '.$file);

            if (is_dir($file)) {
                Logger::getInstance()->debug($file.' is directory. Recursively search the file list.');
                $iterator = new \RegexIterator(
                    new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($file)),
                    '/^.+\.php$/i'
                );

                foreach ($iterator as $file_obj) {
                    Logger::getInstance()->debug('Add: '.$file_obj->getPathname());
                    $this->files[] = $file_obj->getPathname();
                }
            } elseif (is_file($file)) {
                Logger::getInstance()->debug($file.' is file. Add it to files.');
                $this->files[] = $file;
            } else {
                Logger::getInstance()->debug($file.' is unknow. Ignore it.');
                # noop
            }
        }

        $this->tools = Tool::create();

        Logger::getInstance()->info('Target files: '.count($this->files));
        Logger::getInstance()->info('Tools: '.count($this->tools));
    }

    public function instruct()
    {
        foreach ($this->files as $file) {
            Logger::getInstance()->info('Parse: '.$file);
            $root = \ast\parse_file($file, 40);
            $this->traverse($file, $root);
        }

        Logger::getInstance()->info('Hints: '.count($this->hints));
    }

    private function traverse(string $file, Node $node)
    {
        Logger::getInstance()->debug('Traverse: '.\ast\get_kind_name($node->kind));

        foreach ($this->tools as $tool) {
            Logger::getInstance()->debug('Entrypoint check: '.get_class($tool));
            if (get_class($tool)::ENTRY_POINT === $node->kind) {
                Logger::getInstance()->debug('Run: '.get_class($tool));
                $hint = $tool->run($file, $node);
                if ($hint) {
                    Logger::getInstance()->debug('Detected hints: line='.$hint->lineno);
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
