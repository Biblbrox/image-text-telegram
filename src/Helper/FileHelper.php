<?php


namespace TextOnImage\Helper;

/**
 * Class FileHelper
 * @package TextOnImage\Helper
 */
class FileHelper
{
    /**
     * @param $dir
     */
    static function deleteFilesInDir($dir)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException('$dir parameter must be a directory');
        }
        $files = glob(substr($dir, -1) === "/" ? $dir . "*" : $dir . "/*");
        foreach ($files as $file) {
            self::deleteFile($file);
        }
    }

    /**
     * @param $file
     */
    static function deleteFile($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException('$file parameter must be a file');
        }
        if (is_file($file)) {
            unlink($file);
        }
    }
}