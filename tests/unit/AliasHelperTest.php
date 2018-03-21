<?php

use TextOnImage\Helper\AliasHelper;

class AliasHelperTest extends \Codeception\Test\Unit
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


    //getPath tests
    public function testAliasToRootIsRight()
    {
        $this->assertEquals("/home/staralex/textImageGenerator", AliasHelper::getPath("@root"));
    }

    public function testAliasToRootIsRightWithEndSlash()
    {
        $this->assertEquals("/home/staralex/textImageGenerator", AliasHelper::getPath("@root/"));
    }

    public function testAliasToPhotosFolderIsRight()
    {
        $this->assertEquals("/home/staralex/textImageGenerator/Download/photos", AliasHelper::getPath("@root/Download/photos"));
    }

    public function testAliasToPhotosFolderWithSlashIsRight()
    {
        $this->assertEquals("/home/staralex/textImageGenerator/Download/photos", AliasHelper::getPath("@root/Download/photos/"));
    }

    public function testDifferentAliases()
    {
        $this->assertEquals("/home/staralex/textImageGenerator/Config", AliasHelper::getPath("@config"));
        $this->assertEquals("/home/staralex/textImageGenerator/res", AliasHelper::getPath("@res"));
        $this->tester->expectException(new Exception("Alias '@wrong' doesn't exist"), function () {
            $this->assertEquals("/home/staralex/textImageGenerator/res", AliasHelper::getPath("@wrong"));
        });
        $this->tester->expectException(new Exception("Alias '@wrong' doesn't exist"), function () {
            $this->assertEquals("/home/staralex/textImageGenerator/res", AliasHelper::getPath("@wrong/some/path"));
        });
    }
    //End getPath tests
}