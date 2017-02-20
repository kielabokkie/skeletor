<?php
namespace Skeletor\Manager;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use League\CLImate\CLImate;

class ComposerManager
{
    protected $cli;

    public function __construct(CLImate $cli)
    {
        $this->cli = $cli;
    }

    public function prepareFrameworkCommand(string $framework, string $name, string $version)
    {
        return sprintf('create-project --prefer-dist --ansi %s/%s:%s', $framework, $name, $version);
    }

    public function runCommand(string $command)
    {
        $this->cli->yellow($command);
        $process = new Process($command);
        $process->setTimeout(500);

        // Stream output to the cli
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if ($process->isSuccessful() === false) {
            $this->success = false;
            throw new ProcessFailedException($process);
        }
    }
}