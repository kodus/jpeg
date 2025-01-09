<?php

namespace Kodus;

use RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * This class implements a simple service-wrapper around the `jpeg-recompress` tool.
 *
 * @link https://github.com/danielgtaylor/jpeg-archive
 */
class JPEGService
{
    /**
     * @param string|null $bin_path optional path to `jpeg-recompress` binary (defaults to a built-in binary)
     * @param string      $args     command-line arguments for `jpeg-recompress`, with {INPUT} and {OUTPUT} placeholders
     */
    public function __construct(
        private ?string $bin_path = null,
        private string $args = "--min 50 {INPUT} {OUTPUT}"
    ) {
        if ($bin_path === null) {
            $bin_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . "bin" . DIRECTORY_SEPARATOR;

            if (strncasecmp(PHP_OS, "LINUX", 5) === 0) {
                $this->bin_path = $bin_dir . "linux" . DIRECTORY_SEPARATOR . "jpeg-recompress";
            } elseif (strncasecmp(PHP_OS, "DARWIN", 6) === 0) {
                $this->bin_path = $bin_dir . "mac" . DIRECTORY_SEPARATOR . "jpeg-recompress";
            } elseif (strncasecmp(PHP_OS, "WIN", 3) === 0) {
                $this->bin_path = $bin_dir . "win" . DIRECTORY_SEPARATOR . "jpeg-recompress.exe";
            } else {
                throw new RuntimeException("unsupported OS: " . PHP_OS);
            }
        }
    }

    /**
     * Compress a specified JPEG file and write to a specified output path.
     *
     * @param string $input  absolute path to input JPEG file
     * @param string $output absolute path of output JPEG file
     *
     * @throws ProcessFailedException on failure to execute the command-line tool
     */
    public function compress(string $input, string $output): void
    {
        $command = strtr(
            "{$this->bin_path} {$this->args}",
            [
                "{INPUT}"  => escapeshellarg($input),
                "{OUTPUT}" => escapeshellarg($output),
            ]
        );

        $process = Process::fromShellCommandline($command);

        $process->mustRun();
    }

    /**
     * Compress JPEG data from a given input stream and write the output data
     * either to a given stream, or to a temporary stream, which will be returned.
     *
     * @param resource      $input  input stream resource
     * @param resource|null $output optional output stream resource
     *
     * @return resource output stream resource (file pointer will be at the start of the stream)
     *
     * @throws ProcessFailedException on failure to execute the command-line tool
     */
    public function compressStream($input, &$output = null)
    {
        $command = strtr(
            "{$this->bin_path} {$this->args}",
            [
                "{INPUT}"  => "-",
                "{OUTPUT}" => "-",
            ]
        );

        $process = Process::fromShellCommandline($command);

        $process->setInput($input);

        if ($output === null) {
            $output = fopen("php://temp", "w");
        }

        $status = $process->run(function ($type, $buffer) use ($output) {
            if ($type === Process::OUT) {
                fwrite($output, $buffer);
            }
        });

        if ($status !== 0) {
            throw new ProcessFailedException($process);
        }

        rewind($output);

        return $output;
    }
}
