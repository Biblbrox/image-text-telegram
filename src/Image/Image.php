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

    /**
     * @var string $imagePath
     */
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
     * @param string $pos
     * @throws \Exception
     */
    public function addText($text, $pos = "middle")
    {
        $fontSize = TextConfig::getInstance()->getFontSize();
        $this->text = TextHelper::splitToRows($text, $this->image);
        $draw = new ImagickDraw();
        $_pos = lcfirst($pos);

        $draw->setTextAntialias(true);
        $draw->setFontSize($fontSize);
        $draw->setStrokeColor(Color::rgb(0, 0, 0, 120));
        $draw->setFillColor(Color::rgb(0, 0, 0, 120));
        switch ($_pos) {
            case "top":
                $draw->rectangle(0, 0, $this->image->getImageWidth(), $this->image->getImageHeight() / 3);
                break;
            case "middle":
                $draw->rectangle(0, $this->image->getImageHeight() / 3, $this->image->getImageWidth(), $this->image->getImageHeight() / 3 * 2);
                break;
            case "bottom":
                $this->text->setRows(array_reverse($this->text->getRows()));
                $draw->rectangle(0, $this->image->getImageHeight() / 3 * 2, $this->image->getImageWidth(), $this->image->getImageHeight());
                break;
            default:
                break;
        }

        $draw->setFont(TextConfig::getInstance()->getFont());
        switch ($_pos) {
            case "top":
                $draw->setGravity(Imagick::GRAVITY_NORTH);
                break;
            case "middle":
                $draw->setGravity(Imagick::GRAVITY_CENTER);
                break;
            case "bottom":
                $draw->setGravity(Imagick::GRAVITY_SOUTH);
                break;
        }
        $draw->setFillColor(Color::rgb(255, 255, 255));

        $padding = 0;
        $fix = 120;
        foreach ($this->text as $row) {
            switch ($_pos) {
                case "top":
                    $draw->annotation(0, $fix - $this->text->getArea()->getHeight() / 2 + $padding, $row->getText());
                    break;
                case "middle":
                    $draw->annotation(0, -$this->text->getArea()->getHeight() / 2 + $padding, $row->getText());
                    break;
                case "bottom":
                    $draw->annotation(0, $fix - $this->text->getArea()->getHeight() / 2 + $padding, $row->getText());
                    break;
            }
            $padding += TextConfig::getInstance()->getLineSpacing();
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