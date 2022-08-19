<?php

namespace Kodus\JPEG\Test\Unit;

use Kodus\JPEGService;
use Tests\Support\UnitTester;

class CompressJPEGStreamCest
{
    public function testCompressJPEGStream(UnitTester $I): void
    {
        $I->wantTo('compress a JPEG stream');

        $service = new JPEGService();

        $input = fopen(codecept_data_dir('/lena.jpg'), "r");

        $output = $service->compressStream($input);

        $tmp_file = codecept_output_dir('lena-out.jpg');

        @unlink($tmp_file); // clean up from prior test-run

        $tmp = fopen($tmp_file, "w");

        stream_copy_to_stream($output, $tmp);

        fclose($tmp);

        list($width, $height) = getimagesize($tmp_file);

        $I->assertEquals(512, $width, 'width is correct');
        $I->assertEquals(512, $height, 'height is correct');
    }
}
