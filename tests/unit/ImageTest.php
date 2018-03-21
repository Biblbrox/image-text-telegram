<?php

use TextOnImage\Helper\AliasHelper;
use TextOnImage\Image\Image;

class ImageTest extends \Codeception\Test\Unit
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

    public function testSetImage()
    {
        $wrongPath = "asdasdsads/asd.asdasd/ad.pasda";
        $this->tester->expectException(new InvalidArgumentException("File by path must be image"), function () use ($wrongPath) {
            new Image($wrongPath);
        });

        $truePath = AliasHelper::getPath("@res/courbd.ttf");
        $this->assertTrue((new Image($truePath))->getImage() instanceof Imagick);
    }

}