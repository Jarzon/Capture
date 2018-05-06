<?php
namespace Jarzon;

class Capture
{
    protected $options;

    public function __construct(array $options = [])
    {
        $this->options = $options += [
            'browser' => 'chrome',
            'chrome_path' => '/usr/bin/google-chrome',
            'firefox_path' => '/usr/bin/firefox'
        ];
    }

    public function screenshot(string $url, string $destination, $async = true)
    {
        $flags = '';

        if($this->options['browser'] == 'chrome') $flags = "--hide-scrollbars --screenshot=\"$destination\"";
        else if($this->options['browser'] == 'firefox') $flags = "-screenshot $destination";

        return $this->buildCommand($flags, $url, $async);
    }

    public function pdf(string $url, string $destination, $async = true) : array
    {
        if($this->options['browser'] == 'firefox') throw new \Exception('Firefox pdf capture isn\'t supported');

        return $this->buildCommand("--print-to-pdf=\"{$destination}\"", $url, $async);
    }

    public function html(string $url) : string
    {
        if($this->options['browser'] == 'firefox') throw new \Exception('Firefox html capture isn\'t supported');

        $output = $this->buildCommand("--dump-dom", $url, false);

        return implode("\n", $output);
    }

    private function buildCommand(string $flags, string $url, bool $async = true) : array
    {
        if($this->options['browser'] == 'chrome') $flags = "--headless --disable-gpu $flags";
        else if($this->options['browser'] == 'firefox') $flags = "-headless $flags";

        return $this->exec("{$this->options["{$this->options['browser']}_path"]} $flags $url", $async);
    }

    public function exec(string $cmd, bool $async = true) : array
    {
        $output = [];

        if ($this::isWindows() && $async) {
            pclose(popen("start /B \"cmd title\" $cmd", "r"));
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