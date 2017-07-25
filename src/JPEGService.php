<?php

namespace Kodus;

use RuntimeException;

/**
 * This class implements a simple service-wrapper around the `jpeg-recompress` tool.
 *
 * @link https://github.com/danielgtaylor/jpeg-archive
 */
class JPEGService
{
    /**
     * @var string
     */
    private $bin_path;

    /**
     * @var string
     */
    private $args;

    /**
     * @param string|null $bin_path optional path to `jpeg-recompress` binary (defaults to a built-in binary)
     * @param string      $args     command-line arguments for `jpeg-recompress`, with {INPUT} and {OUTPUT} placeholders
     */
    public function __construct(string $bin_path = null, string $args = "--min 50 --quiet {INPUT} {OUTPUT}")
    {
        if ($bin_path === null) {
            $bin_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR;

            if (strncasecmp(PHP_OS, 'LINUX', 5) === 0) {
                $bin_path = $bin_dir . DIRECTORY_SEPARATOR . "linux" . DIRECTORY_SEPARATOR . "jpeg-recompress";
            } elseif (strncasecmp(PHP_OS, 'DARWIN', 6) === 0) {
                $bin_path = $bin_dir . DIRECTORY_SEPARATOR . "mac" . DIRECTORY_SEPARATOR . "jpeg-recompress";
            } elseif (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
                $bin_path = $bin_dir . DIRECTORY_SEPARATOR . "win" . DIRECTORY_SEPARATOR . "jpeg-recompress.exe";
            } else {
                throw new RuntimeException("unsupported OS: " . PHP_OS);
            }
        }

        $this->bin_path = $bin_path;
        $this->args = $args;
    }

    /**
     * @param string $input  absolute path to input JPEG file
     * @param string $output absolute path of output JPEG file
     *
     * @throws RuntimeException on failure to execute the tool
     */
    public function compress(string $input, string $output)
    {
        $command = strtr(
            "{$this->bin_path} {$this->args}",
            [
                "{INPUT}" => escapeshellarg($input),
                "{OUTPUT}" => escapeshellarg($output),
            ]
        );

        @exec($command, $output, $status);

        if ($status !== 0) {
            $output = implode("\n", $output);

            throw new RuntimeException("command failed with status: {$status}\n> {$command}\n{$output}", $status);
        }
    }
}
