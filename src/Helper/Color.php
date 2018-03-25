<?php


namespace TextOnImage\Helper;

/**
 * Class Color
 * @package TextOnImage\Helper
 */
class Color
{

    /**
     * @param $r
     * @param $g
     * @param $b
     * @param int $a
     * @return \ImagickPixel
     */
    public static function rgb($r, $g, $b, $a = 255)
    {
        if (($r < 0 || $r > 255)
            || ($g < 0 || $g > 255)
            || ($b < 0 || $b > 255)
            || ($a < 0 || $a > 255)) {
            throw new \InvalidArgumentException("The color r, g, b must be in range (0, 255)");
        }

        $_r = dechex($r);
        $_g = dechex($g);
        $_b = dechex($b);
        $_a = dechex($a);

        if (intval(hexdec($_r) / 10, 10) === 0) {
            $_r = "0" . $_r;
        }
        if (intval(hexdec($_g) / 10, 10) === 0) {
            $_g = "0" . $_g;
        }
        if (intval(hexdec($_b) / 10, 10) === 0) {
            $_b = "0" . $_b;
        }
        if (intval(hexdec($_a) / 10, 10) === 0) {
            $_a = "0" . $_a;
        }

        return new \ImagickPixel("#$_r" . "$_g" . "$_b" . "$_a");
    }
}