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
     * @var CLImate padding method
     */
    protected $padding;

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

    /**
     * @var bool success
     */
    protected $success;

    public function __construct(CLImate $cli, FrameworkManager $frameworkManager)
    {
        parent::__construct();

        $this->cli = new CLImate;
        $this->padding = $this->cli->padding(20);

        $adapter = new Local(getcwd());
        $this->filesystem = new Filesystem($adapter);

        $this->success = false;
        $this->setPackages();

        $frameworkManager = new FrameworkManager();
        $frameworkManager->addFramework(new Laravel54Framework());
        $frameworkManager->addFramework(new Laravel53Framework());
        $frameworkManager->addFramework(new Lumen54Framework());
        $frameworkManager->addFramework(new Symfony31Framework());
    }

    public function __destruct()
    {
        if($this->success) {
            $this->cli->br()->green('Yhea, success')->br();
        } else {
            $this->cli->br()->red('Owh no, it failed')->br();
        }
    }

    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new Laravel/Lumen project skeleton');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Say hello
        $this->cli->br()->yellow('Skeletor - Laravel/Lumen project creator')->br();

        // Set all options
        $this->setOptions();

        // Set default packages
        $this->setDefaultPackages();

        // Get all options before confirmations
        $this->getOptions();

        // Check options
        if ($this->confirmOptions() === false) {
            return false;
        }

        // Build project
        $this->buildProject();
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
        $this->cli->dump($this->activePackages);
        $this->activePackages = array_map(function($activePackage) {
            return $this->availablePackages[$activePackage];
        }, $packagesResponse);
    }

    private function getOptions()
    {
        $this->cli->br()->yellow('Project setup:');
        $this->padding->label('Framework')->result($this->framework);
        $this->padding->label('Version')->result($this->frameworkVersion);
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

        $this->success = true;
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