<?php
namespace Skeletor\Manager;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class RunManager extends Manager
{
    /**
     * @param string $command
     */
    public function runCommand(string $command)
    {
        $this->cli->br()->yellow($command);

        if ($this->options['dryRun']) {
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
