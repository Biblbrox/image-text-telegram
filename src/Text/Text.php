<?php

namespace TextOnImage\Text;

/**
 * Class Text
 * @package TextOnImage\Text
 */
class Text implements \Iterator, \Countable
{
    /**
     * @var int $fontSize
     */
    private $fontSize;

    /**
     * @var array $rows
     */
    private $rows = [];

    /**
     * @var TextArea $area
     */
    private $area;

    /**
     * @var string $fontLocation
     */
    private $fontLocation;

    /**
     * Text constructor.
     * @param string $fontLocation
     * @param int $fontSize
     */
    public function __construct(string $fontLocation, int $fontSize)
    {
        $this->fontLocation = $fontLocation;
        $this->fontSize = $fontSize;
        $this->area = new TextArea($this, $fontLocation, $fontSize);
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param array $rows
     */
    public function setRows(array $rows = [])
    {
        $this->rows = $rows;
    }

    /**
     * @param $text
     * @param int $number
     */
    public function appendRows($text, $number = 1)
    {
        foreach (range(0, $number) as $i) {
            $this->appendRow($text);
        }
    }

    /**
     *
     * @param $text
     */
    public function appendRow($text)
    {
        $this->rows[] = new Row($text);
        $this->area->setText($this);
    }

    /**
     * @return int
     */
    public function getMaxWidth() : int
    {
        $size = imagettfbbox(TextConfig::getInstance()->getFontSize(), 0, TextConfig::getInstance()->getFont(), $this->getRow(0)->getText());
        $width = $size[2] - $size[0];
        $max = $width;
        foreach ($this->rows as $row) {
            $size = imagettfbbox(TextConfig::getInstance()->getFontSize(), 0, TextConfig::getInstance()->getFont(), $row->getText());
            $width = $size[2] - $size[0];
            if ($max < $width) {
                $max = $width;
            }
        }

        return $width;
    }

    /**
     * @param $key
     * @return Row|null
     */
    public function getRow($key) : Row
    {
        return isset($this->rows[$key]) ? $this->rows[$key] : null;
    }

    /**
     * @return mixed
     */
    public function getFontSize() : int
    {
        return $this->fontSize;
    }

    /**
     * @param mixed $fontSize
     */
    public function setFontSize($fontSize) : void
    {
        $this->fontSize = $fontSize;
    }

    /**
     * @param mixed $text
     */
    public function setText($text) : void
    {
        if (is_object($text) && !method_exists($text, '__String')) {
            throw new \InvalidArgumentException("Possible concat only string or objects with __toString method");
        }

        $this->rows = (string) $text;
    }

    /**
     * @return int
     */
    public function getTextWidth() : int
    {
        return $this->area->getWidth();
    }

    /**
     * @return int
     */
    public function getTextHeight() : int
    {
        return $this->area->getHeight();
    }

    /**
     * @return TextArea
     */
    public function getArea(): TextArea
    {
        return isset($this->area) ? $this->area : new TextArea($this, $this->fontLocation, $this->fontSize);
    }

    /**
     * @return string
     */
    public function getFontLocation() : string
    {
        return $this->fontLocation;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->rows);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->rows);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->rows);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return null !== key($this->rows);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->rows);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->rows);
    }
}