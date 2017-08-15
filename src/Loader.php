<?php declare(strict_types=1);

namespace Pahout;

/**
* Loader that loads files and directories recursively.
*/
class Loader
{
    /**
    * Dig file paths from files or directories.
    *
    * If a directory name received, it digs recursively under the directory.
    *
    * @param string[] $files List of file names and directory names to load.
    * @return string[] List of readable file paths.
    */
    public static function dig(array $files): array
    {
        $list = [];

        foreach ($files as $file) {
            Logger::getInstance()->info('Load: '.$file);

            // If a directory name received, it digs recursively under the this directory.
            if (is_dir($file)) {
                Logger::getInstance()->debug($file.' is directory. Recursively search the file list.');
                $iterator = new \RegexIterator(
                    new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($file)),
                    '/^.+\.php$/i'
                );

                foreach ($iterator as $file_obj) {
                    Logger::getInstance()->debug('Add: '.$file_obj->getPathname());
                    $list[] = $file_obj->getPathname();
                }
            // If a file name received, it adds this file to list.
            } elseif (is_file($file)) {
                Logger::getInstance()->debug($file.' is file. Add it to files.');
                $list[] = $file;
            // If the received name is neither a file nor a directory, it ignores this.
            } else {
                Logger::getInstance()->debug($file.' is unknown object. Ignore it.');
            }
        }

        return $list;
    }
}
