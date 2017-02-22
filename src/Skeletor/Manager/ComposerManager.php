<?php
namespace Skeletor\Manager;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use League\CLImate\CLImate;

class ComposerManager
{
    protected $cli;
    protected $dryRun;

    public function __construct(CLImate $cli, bool $dryRun)
    {
        $this->cli = $cli;
        $this->dryRun = $dryRun;
    }

    public function prepareFrameworkCommand(string $framework, string $version)
    {
        return sprintf('composer create-project --prefer-dist --ansi %s:%s .', $framework, $version);
    }

    public function preparePackageCommand(string $package, string $version, string $options)
    {
        return sprintf('composer require %s %s %s', $package, $version, $options);
    }

    public function runCommand(string $command)
    {
        $this->cli->yellow($command);

        if($this->dryRun) {
            return;
        }

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