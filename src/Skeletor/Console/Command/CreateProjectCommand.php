<?php
namespace Skeletor\Console\Command;

use League\Container\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProjectCommand extends Command
{
    /**
     * @var Instance of CLI
     */
    protected $cli;

    /**
     * @var Instace of Framework Manager
     */
    protected $frameworkManager;

    /**
     * @var Instace of Package Manager
     */
    protected $packageManager;

    /**
     * @var array with active packages
     */
    protected $activePackages;

    /**
     * @var Instance of Framework
     */
    protected $activeFramework;

    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new Laravel/Lumen project skeleton')
            ->addOption('dryrun', null, InputOption::VALUE_NONE, 'Dryrun the install', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dryRun = $input->getOption('dryrun');
        $this->getApplication()->registrateServices($dryRun);
        $this->setupCommand();

        $this->cli->br()->yellow(sprintf('Skeletor - %s project creator', implode(" / ", $this->frameworkManager->getFrameworkNames()) ))->br();

        $this->activeFramework = $this->frameworkManager->getFrameworkOption();
        $this->activePackages = $this->packageManager->getPackageOptions();

        if ($this->confirmOptions("Specify package versions?")) {
            $this->packageManager->specifyPackagesVersions($this->activePackages);
        }

        $this->showEnteredOptions();
        if (!$this->confirmOptions()) {
            return false;
        }

        $this->activePackages = $this->packageManager->mergeSelectedAndDefaultPackages($this->activePackages);
        $this->buildProject();
        $this->cli->br()->green('Yhea, success')->br();
    }

    private function setupCommand()
    {
        $this->frameworkManager = $this->getApplication()->container->get('FrameworkManager');
        $this->packageManager = $this->getApplication()->container->get('PackageManager');
        $this->cli = $this->getApplication()->container->get('Cli');

        $this->frameworkManager->setFrameworks($this->getApplication()->getFrameworks());
        $this->packageManager->setPackages($this->getApplication()->getPackages());
        $this->packageManager->setDefaultPackages($this->getApplication()->getDefaultPackages());
    }

    private function showEnteredOptions()
    {
        $padding = $this->cli->padding(20);
        $this->cli->br()->yellow('Project setup:');
        $padding->label('Framework')->result($this->activeFramework->getName());
        $padding->label('Version')->result($this->activeFramework->getVersion());
        $this->cli->br()->yellow('Packages:');
        $this->cli->table($this->packageManager->showPackagesTable($this->activePackages));
    }

    private function confirmOptions($text = "Continue?")
    {
        $input = $this->cli->confirm($text);
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

        foreach($this->activePackages as $key => $package) {
            $this->packageManager->install($package);
            $this->packageManager->tidyUp($package);
        }
    }
}