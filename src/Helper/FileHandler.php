<?php


namespace TextOnImage\Helper;

class FileHandler
{
    /**
     * @param $dir
     */
    static function deleteFilesInDir($dir)
    {
        $files = glob(substr($dir, -1) === "/" ? $dir . "*" : $dir . "/*");
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}