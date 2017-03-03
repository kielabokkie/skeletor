<?php
namespace Skeletor\Manager;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ComposerManager extends Manager implements InstallerInterface
{
    /**
     * @param string $framework
     * @param string $version
     * @return string
     */
    public function prepareFrameworkCommand(string $framework, string $version)
    {
        return sprintf('composer create-project --prefer-dist --ansi %s:%s .', $framework, $version);
    }

    /**
     * @param string $package
     * @param string $version
     * @param string $packageOptions
     * @return string
     */
    public function preparePackageCommand(string $package, string $version, string $packageOptions)
    {
        return sprintf('composer require %s %s %s', $package, $version, $packageOptions);
    }

    /**
     * @param string $command
     */
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