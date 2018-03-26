<?php

namespace TextOnImage\Image;

use Imagick;
use ImagickDraw;
use InvalidArgumentException;
use TextOnImage\Text\TextConfig;
use TextOnImage\Text\Text;
use TextOnImage\Helper\Color;
use TextOnImage\Helper\TextHelper;

/**
 * Class Image
 * @package TextOnImage\App
 */
class Image
{
    /**
     * Top text position constant
     */
    public const TOP_POS = "top";

    /**
     * Middle text position constant
     */
    public const MIDDLE_POS = "middle";

    /**
     * Bottom text position constant
     */
    public const BOTTOM_POS = "bottom";

    /**
     * @var Imagick $image
     */
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
    public function addText($text, $pos = self::MIDDLE_POS) : void
    {
        $fontSize = TextConfig::getInstance()->getFontSize();
        $this->text = TextHelper::splitToRows($text, $this->image);
        $draw = new ImagickDraw();
        $_pos = lcfirst($pos);

        $draw->setTextAntialias(true);
        $draw->setFontSize($fontSize);
        $draw->setStrokeColor(Color::rgb(0, 0, 0, 120));
        $draw->setFillColor(Color::rgb(0, 0, 0, 120));
        if ($this->text->getTextHeight() > $this->image->getImageHeight() / 3 - 30) {
            $_pos = self::MIDDLE_POS;
        }
        $imageHeight = $this->image->getImageHeight();
        $imageWidth = $this->image->getImageWidth();
        $textHeight = $this->text->getTextHeight();
        switch ($_pos) {
            case self::TOP_POS:
                $draw->setGravity(Imagick::GRAVITY_NORTH);
                break;
            case self::MIDDLE_POS:
                $draw->setGravity(Imagick::GRAVITY_CENTER);
                break;
            case self::BOTTOM_POS:
                $draw->setGravity(Imagick::GRAVITY_SOUTH);
                break;
        }
        switch ($_pos) {
            case self::TOP_POS:
                $draw->rectangle(0, 0, $imageWidth, $imageHeight / 7 + $textHeight / 2 + 40);
                break;
            case self::MIDDLE_POS:
                $draw->rectangle(0, $imageHeight / 3, $imageWidth,  $imageHeight / 3 * 2);
                break;
            case self::BOTTOM_POS:
                $this->text->setRows(array_reverse($this->text->getRows()));
                $draw->rectangle(0, $imageHeight - $imageHeight / 7 - $textHeight / 2 - 40, $imageWidth, $imageHeight);
                break;
            default:
                break;
        }

        $draw->setFont(TextConfig::getInstance()->getFont());
        $draw->setFillColor(Color::rgb(255, 255, 255));

        $padding = 0;
        $fix = 120;
        foreach ($this->text as $row) {
            switch ($_pos) {
                case self::TOP_POS:
                    $draw->annotation(0, $fix - $this->text->getArea()->getHeight() / 2 + $padding, $row->getText());
                    break;
                case self::MIDDLE_POS:
                    $draw->annotation(0, -$this->text->getArea()->getHeight() / 2 + $padding, $row->getText());
                    break;
                case self::BOTTOM_POS:
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
    public function setImage($imagePath) : void
    {
        $this->image = new Imagick($imagePath);
        $this->imagePath = $imagePath;
    }

    /**
     * @return bool
     */
    public function imageLoaded() : bool
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