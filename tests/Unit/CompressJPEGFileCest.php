<?php

namespace Kodus\JPEG\Test\Unit;

use Kodus\JPEGService;
use Tests\Support\UnitTester;

class CompressJPEGFileCest
{
    public function testCompressJPEGFile(UnitTester $I): void
    {
        $I->wantTo('compress a JPEG file');

        $service = new JPEGService();

        $input = codecept_data_dir('lena.jpg');
        $output = codecept_output_dir('/lena-out.jpg');

        @unlink($output); // clean up from prior test-run

        $service->compress($input, $output);

        $I->assertTrue(file_exists($output), 'Output file was created');

        $I->assertTrue(filesize($output) < filesize($input) * 0.7, "image has been compressed");

        list($width, $height) = getimagesize($output);

        $I->assertEquals(512, $width, 'width is correct');
        $I->assertEquals(512, $height, height is correct');
    }
}
