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

        $config = Config::getInstance();
        // Set a list of analysis target files.
        $this->files = array_filter(Loader::dig($files), function ($file) use ($config) {
            return !in_array(realpath($file), $config->ignore_paths);
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
            try {
                $root = \ast\parse_file($file, Config::AST_VERSION);
                // If file is empty, $root is not instance of Node.
                if ($root instanceof Node) {
                    $this->traverse($file, $root);
                }
            } catch (\ParseError $exception) {
                // When a parsing error occurs, the file is determined to be a syntax error.
                Logger::getInstance()->info('Parse error occurred: '.$file);
                // SyntaxError is a special tool. Pahout directly generates Hint without checking AST.
                if (!in_array('SyntaxError', Config::getInstance()->ignore_tools, true)) {
                    $this->hints[] = new Hint(
                        'SyntaxError',
                        $exception->getMessage(),
                        $exception->getFile(),
                        $exception->getLine()
                    );
                }
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
            if ($tool::ENTRY_POINT === $node->kind) {
                Logger::getInstance()->debug('Run: '.get_class($tool));
                $hints = $tool->run($file, $node);
                if (count($hints) > 0) {
                    foreach ($hints as $hint) {
                        Logger::getInstance()->debug('Detected hints: line='.$hint->lineno);
                    }
                    array_push($this->hints, ...$hints);
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
