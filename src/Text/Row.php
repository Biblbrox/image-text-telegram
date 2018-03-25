<?php


namespace TextOnImage\Text;

/**
 * Class Row
 * @package TextOnImage\Text
 */
class Row
{
    /**
     * @var
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

        if (!is_string($text)) {
            throw new \InvalidArgumentException("Possible concat only string or objects with __toString method");
        }

        $this->text = (string) $text;
    }

     /**
     * @param $str
     */
    public function concat($str)
    {
        if (is_object($str) && !method_exists($str, '__String')) {
            throw new \InvalidArgumentException("Possible concat only string or objects with __toString method");
        }

        if (!is_string($str)) {
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
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        if (is_object($text) && !method_exists($text, '__String')) {
            throw new \InvalidArgumentException("Possible concat only string or objects with __toString method");
        }

        if (!is_string($text)) {
            throw new \InvalidArgumentException("Possible concat only string or objects with __toString method");
        }

        $this->text = $text;
    }


}