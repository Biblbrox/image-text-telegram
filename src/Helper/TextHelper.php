<?php


namespace TextOnImage\Helper;


use Imagick;
use TextOnImage\Text\Text;
use TextOnImage\Text\TextArea;

/**
 * Class TextHelper
 * @package TextOnImage\Helper
 */
class TextHelper
{
    /**
     * @param string $str
     * @param Imagick $image
     * @param $fontLocation
     * @param $sizeFont
     * @return Text
     */
    public static function adaptTextToImage(string $str, Imagick $image, $fontLocation, $sizeFont) : Text
    {
        $resultStr = new Text($fontLocation, $sizeFont);
        $size = imagettfbbox($sizeFont, 0, $fontLocation, $str);
        $strWidth = $size[2] - $size[0];
        $imageWidth = $image->getImageWidth();
        $padding = 30;

        $imgPaddWidth = $imageWidth - $padding;
        if ($strWidth > $imgPaddWidth) {
            $lim = intval($strWidth / $imgPaddWidth) + 1;

            $words = self::words($str);
            $countWords = count($words);
            if ($countWords === 1) {
                $resultStr->appendRow(substr($str, 0, intval(mb_strlen($str) / 2)));
                $resultStr->appendRow(substr($str, intval(mb_strlen($str) / 2) + 1, mb_strlen($str)));
            } else {
                for ($i = 0; $i < $lim; $i++) {
                    $resultStr->appendRow("");
                }
                for ($i = 0; $i < $lim; $i++) {
                    $start = (intval($countWords / $lim) + 1) * $i;
                    $end = $i === 0 ? intval($countWords / $lim) : (intval($countWords / $lim) + 1) * $i + (intval($countWords / $lim));
                    for ($j = $start; $j <= $end; $j++) {
                        if (isset($words[$j])) {
                            $resultStr->getRow($i)->concat($words[$j] . " ");
                        }
                    }
                }
            }
        }

        return $resultStr;
    }

    /**
     * @param $str
     * @return array
     */
    public static function words($str)
    {
        $words = self::multiExplode(['.', ',', '?', ':', '!', ';', ' '], $str);
        return $words;
    }

    /**
     * @param $delimiters
     * @param $string
     * @return array
     */
    public static function multiExplode($delimiters,$string)
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
}
