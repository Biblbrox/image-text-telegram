<?php

use TextOnImage\Helper\FileHelper;

class FileHelperTest extends \Codeception\Test\Unit
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
        $testDir = __DIR__ . '/sandbox/test';
        if (is_dir($testDir)) {
            rmdir($testDir);
        }
    }

    public function testCreateAndDeleteDir()
    {
        $testDir = __DIR__ . '/sandbox/test';
        if (!is_dir($testDir)) {
            mkdir($testDir);
        }
        FileHelper::deleteDir($testDir);
        $this->assertTrue(!is_dir($testDir));
    }

    public function testCreateAndDeleteDirWithFiles()
    {
        $testDir = __DIR__ . '/sandbox/test';
        if (!is_dir($testDir)) {
            mkdir($testDir);
        }
        touch($testDir . '/one.txt');
        touch($testDir . '/two.txt');
        touch($testDir . '/three.txt');
        FileHelper::deleteDir($testDir);
        $this->assertTrue(!is_dir($testDir));
    }

    public function testCreateAndDeleteFilesInDir()
    {
        $testDir = __DIR__ . '/sandbox/test';
        if (!is_dir($testDir)) {
            mkdir($testDir);
        }
        touch($testDir . '/one.txt');
        touch($testDir . '/two.txt');
        touch($testDir . '/three.txt');
        FileHelper::deleteDir($testDir, FileHelper::ONLY_FILES);
        $this->assertTrue(!file_exists($testDir . '/one.txt'));
        $this->assertTrue(!file_exists($testDir . '/two.txt'));
        $this->assertTrue(!file_exists($testDir . '/three.txt'));
    }

    public function testDeleteNonexistenDir()
    {
        $this->tester->expectException(new InvalidArgumentException('$dir parameter must be a directory'), function () {
            FileHelper::deleteDir("adsaasd");
        });
    }

    public function testDeleteNonexisteFile()
    {
        $this->tester->expectException(new InvalidArgumentException('$file parameter must be a file'), function () {
            FileHelper::deleteFile("adsaasd");
        });
    }

    public function testDeleteDirWithWrongMode()
    {
        $this->tester->expectException(new InvalidArgumentException('Invalid $mode parameter'), function () {
            FileHelper::deleteDir("adsaasd", 999);
        });
    }
}