<?php
namespace Skeletor\Console\Command;

use Skeletor\Frameworks\Laravel54Framework;
use Skeletor\Frameworks\LaravelLumen54Framework;
use Skeletor\Manager\ComposerManager;
use Skeletor\Manager\FrameworkManager;
use League\CLImate\CLImate;
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

    /**
     * @var FrameworkManager
     */
    protected $frameworkManager;

    /**
     * @var array with available packages
     */
    protected $availablePackages;

    /**
     * @var array with active packages
     */
    protected $activePackages;

    /**
     * @var instance of Framework
     */
    protected $activeFramework;


    public function __construct(CLImate $cli, Filesystem $filesystem)
    {
        parent::__construct();
        $this->cli = $cli;
        $this->filesystem = $filesystem;
        $this->setPackages();

        $composerManager = new ComposerManager($this->cli);
        $this->frameworkManager = new FrameworkManager($this->filesystem);

        $this->frameworkManager->addFramework(new Laravel54Framework($composerManager));
        $this->frameworkManager->addFramework(new LaravelLumen54Framework($composerManager));
    }

    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new Laravel/Lumen project skeleton');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->cli->br()->yellow(sprintf('Skeletor - %s project creator', implode(" / ", $this->frameworkManager->getFrameworkNames()) ))->br();
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
        $frameworkOptions = $this->frameworkManager->getFrameworkNames();
        $frameworkQuestion = $this->cli->radio('Choose your framework:', $frameworkOptions);
        $frameworkResponse = $frameworkQuestion->prompt();
        $this->activeFramework = $this->frameworkManager->load($frameworkResponse);

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
        $padding->label('Framework')->result($this->activeFramework->getName());
        $padding->label('Version')->result($this->activeFramework->getVersion());
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
        $this->frameworkManager->install($this->activeFramework);
        $this->frameworkManager->tidyUp($this->activeFramework);
        $this->installPackages();
    }

    private function installPackages()
    {
        foreach($this->activePackages as $key => $package)
        {
            $this->cli->br()->yellow(sprintf('Installing %s %s', $package['name'], $package['version']));
            //$this->runCommand(sprintf('composer require %s %s', $package['slug'], $package['version']));
        }
    }

}