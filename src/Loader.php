<?php declare(strict_types=1);

namespace Pahout;

use Pahout\Exception\InvalidFilePathException;

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
    * @throws InvalidFilePathException Exception when the specified file or directory does not exist.
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
            // The vendor directory is ignored by default,
            // so do not throw an exception if the vendor directory does not exist.
            } elseif ($file === 'vendor') {
                Logger::getInstance()->debug('vendor directory is not found.');
            // If the received name is neither a file nor a directory, it throws an exception.
            } else {
                throw new InvalidFilePathException($file.' is neither a file nor a directory.');
            }
        }

        return $list;
    }
}
