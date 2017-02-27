<?php
namespace Skeletor\Manager;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ComposerManager extends Manager implements InstallerInterface
{
    public function prepareFrameworkCommand(string $framework, string $version)
    {
        return sprintf('composer create-project --prefer-dist --ansi %s:%s .', $framework, $version);
    }

    public function preparePackageCommand(string $package, string $version, string $packageOptions)
    {
        return sprintf('composer require %s %s %s', $package, $version, $packageOptions);
    }

    public function runCommand(string $command)
    {
        $this->cli->yellow($command);

        if($this->options['dryRun']) {
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