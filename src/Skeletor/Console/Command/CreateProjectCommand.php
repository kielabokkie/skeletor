<?php
namespace Skeletor\Console\Command;

use League\CLImate\CLImate;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
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

    /**
     * @var string framework name
     */
    protected $framework;

    /**
     * @var string framework version
     */
    protected $frameworkVersion;

    /**
     * @var array with available packages
     */
    protected $availablePackages;

    /**
     * @var array with active packages
     */
    protected $activePackages;

    public function __construct(CLImate $cli, Filesystem $filesystem)
    {
        parent::__construct();
        $this->cli = $cli;
        $this->filesystem = $filesystem;
        $this->setPackages();
    }

    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new Laravel/Lumen project skeleton');
    }

    protected function execute()
    {
        $this->cli->br()->yellow('Skeletor - Laravel/Lumen project creator')->br();
        $this->setOptions();
        $this->setDefaultPackages();
        $this->showEneteredOptions();

        if ($this->confirmOptions() === false) {
            return false;
        }

        $this->buildProject();
        $this->cli->br()->green('Yhea, success')->br();
    }

    private function setPackages()
    {
        $this->availablePackages = [
            'Behat' => [
                'name' => 'Behat',
                'slug' => 'behat/behat',
                'version' => ''
            ],
            'JSON API Behat Extension' => [
                'name' => 'JSON API Behat Extension',
                'slug' => 'kielabokkie/jsonapi-behat-extension',
                'version' => ' --dev'
            ],
        ];
    }

    private function setDefaultPackages()
    {
        $this->activePackages = array_merge($this->activePackages, [
            [
                'name' => 'PixelFusion Git Hooks',
                'slug' => 'pixelfusion/git-hooks',
                'version' => ''
            ],
        ]);
    }

    private function setOptions()
    {
        // Choose framework
        $frameworkOptions = ['Laravel 5.3', 'Laravel 5.4', 'Lumen 5.4'];
        $frameworkQuestion = $this->cli->radio('Choose your framework:', $frameworkOptions);
        $frameworkResponse = explode(' ', $frameworkQuestion->prompt());
        $this->framework = strtolower($frameworkResponse[0]);
        $this->frameworkVersion = $frameworkResponse[1];

        // Choose packages
        $packagesQuestion = $this->cli->checkboxes('Choose your packages', array_keys($this->availablePackages));
        $packagesResponse = $packagesQuestion->prompt();
        $this->activePackages = array_map(function($activePackage) {
            return $this->availablePackages[$activePackage];
        }, $packagesResponse);
    }

    private function showEneteredOptions()
    {
        $padding = $this->cli->padding(20);

        $this->cli->br()->yellow('Project setup:');
        $padding->label('Framework')->result($this->framework);
        $padding->label('Version')->result($this->frameworkVersion);
        $this->cli->br()->yellow('Packages:');
        $this->cli->table($this->activePackages);
    }

    private function confirmOptions()
    {
        $input = $this->cli->confirm('Continue?');
        if ($input->confirmed()) {
            return true;
        }
        return false;
    }

    private function buildProject()
    {
        $this->cli->br()->green('Building..');
        $this->installFramework();
        $this->installPackages();
    }

    private function runCommand($command)
    {
        $this->cli->yellow($command);
        return;
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

    private function installFramework()
    {
        $this->cli->br()->yellow(sprintf('Installing %s %s', $this->framework, $this->frameworkVersion));
        $this->runCommand(sprintf('composer create-project --prefer-dist --ansi laravel/%s:%s .', $this->framework, $this->frameworkVersion));
        $this->tidyFramework();
    }

    private function tidyFramework()
    {
        // Do some tidying up here
        switch ($this->framework) {
            case 'laravel':
                $this->cli->br()->yellow('Clean laravel');
                break;
            case 'lumen':
                $this->cli->br()->yellow('Clean lumen');
                break;
        }
    }

    private function installPackages()
    {
        foreach($this->activePackages as $key => $package)
        {
            $this->cli->br()->yellow(sprintf('Installing %s %s', $package['name'], $package['version']));
            $this->runCommand(sprintf('composer require %s %s', $package['slug'], $package['version']));
        }
    }
}