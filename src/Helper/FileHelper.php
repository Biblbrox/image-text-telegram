<?php

namespace TextOnImage\Helper;

/**
 * Class FileHelper
 * @package TextOnImage\Helper
 */
class FileHelper
{
    /**
     * Delete only files directory without deleting whole directory
     */
    public const ONLY_FILES = 0;

    /**
     * Delete whole directory
     */
    public const WHOLE_DIR = 1;

    /**
     * @param $file
     */
    static function deleteFile($file)
    {
        if (!is_file($file)) {
            throw new \InvalidArgumentException('$file parameter must be a file');
        }
        if (!is_dir($file)) {
            unlink($file);
        }
    }

    /**
     * @param $dir
     * @param int $mode
     */
    public static function deleteDir($dir, $mode = self::WHOLE_DIR)
    {
        if ($mode !== self::WHOLE_DIR && $mode !== self::ONLY_FILES) {
            throw new \InvalidArgumentException('Invalid $mode parameter');
        }

        if (!is_dir($dir)) {
            throw new \InvalidArgumentException('$dir parameter must be a directory');
        }
        $files = glob(substr($dir, -1) === '/' ? $dir . '*' : $dir . '/*');
        foreach ($files as $file) {
            self::deleteFile($file);
        }
        if ($mode === self::WHOLE_DIR) {
            rmdir($dir);
        }
    }
}