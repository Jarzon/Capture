<?php
namespace Jarzon;

class Capture
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options += [
            'chrome_path' => '/usr/bin/google-chrome'
        ];
    }

    public function screenshot(string $url, string $destination, $async = true)
    {
        return $this->buildCommand("--hide-scrollbars --screenshot=\"{$destination}\"", $url, $async);
    }

    public function pdf(string $url, string $destination, $async = true) : array
    {
        return $this->buildCommand("--print-to-pdf=\"{$destination}\"", $url, $async);
    }

    public function html(string $url) : string
    {
        $output = $this->buildCommand("--dump-dom", $url, false);

        return implode("\n", $output);
    }

    private function buildCommand(string $flags, string $url, bool $async = true) : array
    {
        return $this->exec("{$this->options['chrome_path']} --headless --disable-gpu $flags $url", $async);
    }

    private function exec(string $cmd, bool $async = true) : array
    {
        $output = [];

        if ($this::isWindows() && $async) {
            pclose(popen("start /B ". $cmd, "r"));
        } else {
            if($async) {
                $cmd .= " > /dev/null &";
            }

            exec($cmd, $output);
        }

        return $output;
    }

    public static function isWindows() : bool
    {
        return substr(php_uname(), 0, 7) === "Windows";
    }
}