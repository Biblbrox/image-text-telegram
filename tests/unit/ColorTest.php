<?php

use TextOnImage\Helper\Color;

class ColorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testRightColor()
    {
        $this->assertTrue(Color::rgb(255, 0, 255) instanceof ImagickPixel);
    }

    public function testWrongColor()
    {
        $this->tester->expectException(new InvalidArgumentException("The color r, g, b must be in range (0, 255)"), function() {
            Color::rgb(-1, 0, 255, 1);
        });
    }
}