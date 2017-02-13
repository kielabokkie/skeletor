<?php
namespace Skeletor\Console\Command;

use League\CLImate\CLImate;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CreateProjectCommand extends Command
{
    /**
     * @var CLImate
     */
    protected $cli;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct()
    {
        parent::__construct();

        $this->cli = new CLImate;

        $adapter = new Local(getcwd());
        $this->filesystem = new Filesystem($adapter);
    }

    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new Laravel/Lumen project skeleton');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cli->br()->yellow('Skeletor - Laravel/Lumen project creator')->br();

        $options = ['Laravel 5.3', 'Laravel 5.4', 'Lumen 5.4'];
        $frameworkQuestion = $this->cli->radio('Choose your framework:', $options);
        $response = explode(' ', $frameworkQuestion->prompt());

        $framework = strtolower($response[0]);
        $version = $response[1];

        switch ($framework) {
            case 'laravel':
                $this->cli->br()->yellow('Prepairing to install Laravel')->br();
                $this->installLaravel($version);
                break;
            case 'lumen':
                $this->cli->br()->yellow('Prepairing to install Lumen')->br();
                $this->installLumen($version);
                break;
        }
    }

    private function installLaravel($version)
    {
        $command = sprintf('composer create-project --prefer-dist --ansi laravel/laravel:%s .', $version);

        $process = new Process($command);
        $process->setTimeout(500);

        // Stream output to the cli
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if ($process->isSuccessful() === false) {
            throw new ProcessFailedException($process);
        }

        $this->tidyLaravel();
    }

    private function tidyLaravel()
    {
        // Do some tidying up here
    }

    private function installLumen($version)
    {
        $command = sprintf('composer create-project --prefer-dist --ansi laravel/lumen:%s .', $version);

        $process = new Process($command);
        $process->setTimeout(500);

        // Stream output to the cli
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if ($process->isSuccessful() === false) {
            throw new ProcessFailedException($process);
        }

        $this->tidyLumen();
    }

    private function tidyLumen()
    {
        // Do some tidying up here
    }
}
