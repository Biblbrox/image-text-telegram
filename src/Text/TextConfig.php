<?php

namespace TextOnImage\Text;

/**
 * Class TextConfig
 * @package TextOnImage\Text
 */
final class TextConfig
{
    /**
     * @var TextConfig $instance
     */
    private static $instance;

    /**
     * Path to font file
     * @var string $font
     */
    private $font;

    /**
     * @var int $lineSpacing
     */
    private $lineSpacing;

    /**
     * @var int $fontSize
     */
    private $fontSize;

    /**
     * @return TextConfig
     */
    public static function getInstance() : TextConfig
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * FontConfig constructor.
     */
    private function __construct()
    {
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

    /**
     * @return mixed
     */
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param mixed $fontSize
     */
    public function setFontSize($fontSize): void
    {
        $this->fontSize = $fontSize;
    }
}