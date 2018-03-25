<?php


namespace TextOnImage\Text;


class TextArea
{
    /**
     * @var integer $width
     */
    private $width;

    /**
     * @var integer $height
     */
    private $height;

    /**
     * @var $text
     */
    private $text;

    /**
     * @var string
     */
    private $fontLocation;

    /**
     * @var int
     */
    private $sizeFont;

    /**
     * TextArea constructor.
     * @param Text $text
     * @param string $fontLocation
     * @param int $sizeFont
     */
    public function __construct($text, string $fontLocation, int $sizeFont)
    {
        $this->text = $text;
        $this->fontLocation = $fontLocation;
        $this->sizeFont = $sizeFont;

        $this->recalcArea($fontLocation, $sizeFont);
    }

    /**
     * @param $fontLocation
     * @param $sizeFont
     */
    private function recalcArea($fontLocation, $sizeFont)
    {
        $sizes = [];
        foreach ($this->text as $row) {
            $sizes[] = imagettfbbox($sizeFont, 0, $fontLocation, $row->getText());
        }

        if (isset($sizes[0])) {
            $maxWidth = $sizes[0][2] - $sizes[0][0];
            foreach ($sizes as $size) {
                if ($size[2] - $size[0] > $maxWidth) {
                    $maxWidth = $size[2] - $size[0];
                }
            }

            $this->width = $maxWidth;
            $this->height = ($sizes[0][7] - $sizes[0][1]) * count($sizes)
                + TextConfig::getInstance()->getLineSpacing() * (count($sizes) - 1);
        }
    }


    /**
     * @return int
     */
    public function getWidth() : int
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight() : int
    {
        return $this->height;
    }

    /**
     * @return Text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param Text $text
     */
    public function setText($text)
    {
        $this->text = $text;
        $this->recalcArea($this->fontLocation, $this->sizeFont);
    }


}