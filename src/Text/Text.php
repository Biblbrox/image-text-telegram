<?php

namespace TextOnImage\Text;

class Text implements \Iterator, \Countable
{
    /**
     * @var
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
     * @var
     */
    private $fontLocation;

    /**
     * Text constructor.
     * @param $fontLocation
     * @param $fontSize
     */
    public function __construct($fontLocation, $fontSize)
    {
        $this->fontLocation = $fontLocation;
        $this->fontSize = $fontSize;
        $this->area = new TextArea($this, $fontLocation, $fontSize);
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
    public function getFontSize()
    {
        return $this->fontSize;
    }

    /**
     * @param mixed $fontSize
     */
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
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

        $this->rows = $text . "";
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
     * @return mixed
     */
    public function getFontLocation()
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