<?php

namespace TextOnImage\Text;

/**
 * Class Row
 * @package TextOnImage\Text
 */
class Row
{
    /**
     * @var string $text
     */
    private $text;

    /**
     * Row constructor.
     * @param string $text
     */
    public function __construct(string $text)
    {
        if (is_object($text) && !method_exists($text, '__String')) {
            throw new \InvalidArgumentException("Possible concat only string or objects with __toString method");
        }

        $this->text = (string) $text;
    }

     /**
     * @param $str
     */
    public function concat($str) : void
    {
        if (is_object($str) && !method_exists($str, '__String')) {
            throw new \InvalidArgumentException("Possible concat only string or objects with __toString method");
        }

        $this->text .= $str;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getText() : string
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text) : void
    {
        if (is_object($text) && !method_exists($text, '__String')) {
            throw new \InvalidArgumentException("Possible concat only string or objects with __toString method");
        }

        $this->text = $text;
    }


}