<?php

use Kodus\JPEGService;

require dirname(__DIR__) . '/vendor/autoload.php';

test(
    "compress JPEG file",
    function () {
        $service = new JPEGService();

        $input = __DIR__ . '/lena.jpg';
        $output = __DIR__ . '/lena-out.jpg';

        @unlink($output); // clean up from prior test-run

        $service->compress($input, $output);

        ok(file_exists($output));

        ok(filesize($output) < filesize($input) * 0.7, "image has been compressed");

        list($width, $height) = getimagesize($output);

        eq($width, 512);
        eq($height, 512);
    }
);

test(
    "compress JPEG stream",
    function () {
        $service = new JPEGService();

        $input = fopen(__DIR__ . '/lena.jpg', "r");

        $output = $service->compressStream($input);

        $tmp_file = __DIR__ . '/lena-out.jpg';

        @unlink($tmp_file); // clean up from prior test-run

        $tmp = fopen($tmp_file, "w");

        stream_copy_to_stream($output, $tmp);

        fclose($tmp);

        list($width, $height) = getimagesize($tmp_file);

        eq($width, 512);
        eq($height, 512);
    }
);

exit(run());
