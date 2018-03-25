<?php

namespace TextOnImage\Helper;

use Imagick;
use TextOnImage\Text\Text;
use TextOnImage\Text\TextConfig;

/**
 * Class TextHelper
 * @package TextOnImage\Helper
 */
class TextHelper
{

    /**
     * @param string $str
     * @param Imagick $image
     * @return Text
     */
    public static function splitToRows(string $str, Imagick $image) : Text
    {
        $size = self::lenTTFString($str) / ($image->getImageWidth() - $image->getImageWidth() / 10);

        $chunk = intval(1 / $size * self::lenTTFString($str) / self::symbolWidth($str));
        $result = chunk_split($str, $chunk, "\n");

        $text = new Text(TextConfig::getInstance()->getFont(), TextConfig::getInstance()->getFontSize());
        $newLineCount = substr_count($result, "\n") - 1;
        $text->appendRows("", $newLineCount);
        $s_result = str_split($result);
        unset($s_result[count($s_result) - 1]); // Remove last \n character.
        $p = 0;
        foreach ($text as $row) {
            for ($i = $p; $i < count($s_result); $i++) {
                if ($s_result[$i] === "\n") {
                    $p = $i + 1;
                    continue 2;
                }
                $row->concat($s_result[$i]);
            }
        }

        return $text;
    }

    /**
     * @param $str
     * @return int
     */
    public static function lenTTFString($str)
    {
        $size = imagettfbbox(TextConfig::getInstance()->getFontSize(), 0, TextConfig::getInstance()->getFont(), $str);
        $strWidth = $size[2] - $size[0];
        return $strWidth;
    }

    /**
     * @param $str
     * @return int
     */
    public static function symbolWidth($str)
    {
        $size = imagettfbbox(TextConfig::getInstance()->getFontSize(), 0, TextConfig::getInstance()->getFont(), $str);
        $strWidth = $size[2] - $size[0];
        return intval($strWidth / count(str_split($str)));
    }
}
