<?php
declare(strict_types=1);

namespace Tests;

use Jarzon\Capture;
use PHPUnit\Framework\TestCase;

class CaptureTest extends TestCase
{
    public function testSaveCacheFile()
    {
        $destination = __DIR__ . '/dest/image.png';

        if(file_exists($destination)) unlink($destination);

        $capture = new Capture([
            'chrome_path' => (Capture::isWindows())? '"C:/Program Files (x86)/Google/Chrome/Application/chrome.exe"': '/usr/bin/google-chrome'
        ]);
        
        $capture->screenshot('http://google.ca/', $destination, false);

        $this->assertEquals(true, file_exists($destination));
    }
}