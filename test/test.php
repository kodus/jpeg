<?php

use Kodus\JPEGService;

require dirname(__DIR__) . '/vendor/autoload.php';

test(
    "compress images",
    function () {
        $service = new JPEGService();

        $input = __DIR__ . '/lena.jpg';
        $output = __DIR__ . '/lena-out.jpg';

        $service->compress($input, $output);

        ok(file_exists($output));

        ok(filesize($output) < filesize($input) * 0.7, "image has been compressed");

        list($width, $height) = getimagesize($output);

        eq($width, 512);
        eq($height, 512);
    }
);

exit(run());
