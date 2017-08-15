<?php declare(strict_types=1);

namespace Pahout;

use \ast\Node;
use Pahout\Tool\Base;

/**
* PHP Mahout
*
* Parse the target PHP scripts and traverse AST nodes with DFS.
* It preserves the hints that are obtained.
*/
class Pahout
{
    /** @var Hint[] The hints are obtained from results. */
    public $hints = [];

    /** @var string[] The file names to be analyzed. */
    public $files = [];

    /** @var Base[] The tools provided from tool factory */
    private $tools = [];

    /**
    * Acquire the list of files to be analyzed, and prepare a tool lists.
    *
    * @param string[] $files List of file names and directory names to analyze.
    */
    public function __construct(array $files)
    {
        // If the received files are unspecified, it analyzes recursively under the current directory.
        if (count($files) === 0) {
            Logger::getInstance()->info('Set target to the current directory');
            $files = ['.'];
        }

        // Set a list of analysis target files.
        $this->files = array_filter(Loader::dig($files), function ($file) {
            return !in_array(realpath($file), Config::getInstance()->ignore_paths);
        });

        // Set a list of tool instances matching PHP version.
        $this->tools = ToolBox::create();

        Logger::getInstance()->info('Target files: '.count($this->files));
        Logger::getInstance()->info('Tools: '.count($this->tools));
    }

    /**
    * Tell you about PHP hints.
    *
    * Parses the file to be analyzed and traverses the obtained AST node with DFS.
    *
    * @return void
    */
    public function instruct()
    {
        foreach ($this->files as $file) {
            Logger::getInstance()->info('Parse: '.$file);
            $root = \ast\parse_file($file, 40);
            // If file is empty, $root is not instance of Node.
            if ($root instanceof Node) {
                $this->traverse($file, $root);
            }
        }

        Logger::getInstance()->info('Hints: '.count($this->hints));
    }

    /**
    * Traverse AST nodes with DFS and check the entrypoint of tools.
    *
    * Each time it compares the kind of Node with the entry point of tools.
    * If it matches, it will perform an detection by the tool.
    * Do this process recursively until the children is not a Node.
    *
    * @param string $file File name to be analyzed.
    * @param Node   $node AST node to be analyzed.
    * @return void
    */
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
