<?php

namespace TextOnImage\Image;

use Imagick;
use ImagickDraw;
use InvalidArgumentException;
use TextOnImage\Text\TextConfig;
use TextOnImage\Text\Text;
use TextOnImage\Helper\AliasHelper;
use TextOnImage\Helper\Color;
use TextOnImage\Helper\TextHelper;

/**
 * Class Image
 * @package TextOnImage\App
 */
class Image
{
    private $image;

    /**
     * @var Text $text
     */
    private $text;

    public $imagePath;

    /**
     * Image constructor
     * @param $imagePath
     */
    public function __construct($imagePath = null)
    {
        if (isset($imagePath)) {
            if (!file_exists($imagePath) || is_dir($imagePath)) {
                throw new InvalidArgumentException("File by path must be image");
            }
            $this->image = new Imagick($imagePath);
            $this->imagePath = $imagePath;
        }
    }

    /**
     * @param $text
     * @param int $posX
     * @param int $posY
     * @param int $fontSize
     * @throws \Exception
     */
    public function addText($text, $posX = 0, $posY = 0, $fontSize = 25)
    {
        $this->text = TextHelper::adaptTextToImage($text, $this->image, AliasHelper::getPath("@res/courbd.ttf"), $fontSize);
        $draw = new ImagickDraw();

        $draw->setFontSize($fontSize);
        $draw->setStrokeColor(Color::rgb(0, 0, 0, 120));
        $draw->setFillColor(Color::rgb(0, 0, 0, 120));
        $draw->rectangle(0, $this->image->getImageHeight() / 3, $this->image->getImageWidth(), $this->image->getImageHeight() / 3 * 2);
        $draw->setFont(TextConfig::getInstance()->getFont());
        $draw->setGravity(Imagick::GRAVITY_CENTER);
        $draw->setFillColor(Color::rgb(255, 255, 255));

        if (count($this->text) === 1) {
            $draw->annotation($posX, $posY, $this->text->getRow(0)->getText());
        } else {
            $padding = 0;
            foreach ($this->text as $row) {
                $draw->annotation($posX, $posY - $this->text->getArea()->getHeight() / 2 + $padding, $row->getText());
                $padding += 40;
            }
        }

        $this->image->drawImage($draw);

        $this->image->setImageFormat('png');

        $this->image->writeImageFile(fopen($this->imagePath, "wb"));
        $this->image->destroy();
    }

    /**
     * @param $imagePath
     */
    public function setImage($imagePath)
    {
        $this->image = new Imagick($imagePath);
        $this->imagePath = $imagePath;
    }

    /**
     * @return bool
     */
    public function imageLoaded()
    {
        return isset($this->image);
    }

    /**
     * @return Imagick
     */
    public function getImage(): Imagick
    {
        return $this->image;
    }

}