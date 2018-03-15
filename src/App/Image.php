<?php

namespace TextOnImage\App;

use Imagick;
use ImagickDraw;

class Image
{
    public $image;

    public $draw;

    public $imagePath;

    /**
     * Image constructor.
     * @param $imagePath
     */
    public function __construct($imagePath = null)
    {
        if (isset($imagePath)) {
            $this->image = new Imagick($imagePath);
            $this->imagePath = $imagePath;
        }
    }

    public function addText($text, $posX, $posY, $fontSize = 50)
    {
        $draw = new ImagickDraw();

        $draw->setFontSize($fontSize);

        $draw->annotation($posX, $posY, $text);

        $this->image->drawImage($draw);

        $this->image->setImageFormat('jpeg');

        $this->image->writeImageFile(fopen($this->imagePath, "wb"));
        $this->image->destroy();
    }

    public function setImage($imagePath)
    {
        $this->image = new Imagick($imagePath);
        $this->imagePath = $imagePath;
    }

}