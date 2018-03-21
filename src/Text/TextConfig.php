<?php


namespace TextOnImage\Text;


use TextOnImage\Helper\AliasHelper;

final class TextConfig
{
    private static $instance;

    /**
     * @var
     */
    private $font;

    private $lineSpacing;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self(AliasHelper::getPath("@root/res/courbd.ttf"));
        }

        return self::$instance;
    }

    /**
     * FontConfig constructor.
     * @param string $font
     */
    private function __construct($font)
    {
        $this->setFont($font);
        $this->lineSpacing = 40;
    }

    /**
     * @return mixed
     */
    public function getFont() : string
    {
        return $this->font;
    }

    /**
     * @param mixed $font
     */
    public function setFont($font)
    {
        if (!file_exists($font) || is_dir($font)) {
            throw new \InvalidArgumentException("File $font doesn't exist or being directory");
        }

        $this->font = $font;
    }

    /**
     * @return int
     */
    public function getLineSpacing(): int
    {
        return $this->lineSpacing;
    }

    /**
     * @param int $lineSpacing
     */
    public function setLineSpacing(int $lineSpacing)
    {
        $this->lineSpacing = $lineSpacing;
    }
}