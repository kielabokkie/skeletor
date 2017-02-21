<?php
namespace Skeletor\Console\Command;

use Skeletor\Frameworks\Laravel54Framework;
use Skeletor\Frameworks\LaravelLumen54Framework;
use Skeletor\Packages\BehatPackage;
use Skeletor\Manager\PackageManager;
use Skeletor\Manager\ComposerManager;
use Skeletor\Manager\FrameworkManager;
use League\CLImate\CLImate;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @var PackageManager
     */
    protected $packageManager;

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
    }

    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new Laravel/Lumen project skeleton')
            ->addOption('dryrun', null, InputOption::VALUE_NONE, 'Dryrun the install', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dryRun = $input->getOption('dryrun');
        if($dryRun){
            $this->cli->green('Skeletor runs dry');
        }

        $this->setupDependencies($dryRun);
        $this->cli->br()->yellow(sprintf('Skeletor - %s project creator', implode(" / ", $this->frameworkManager->getFrameworkNames()) ))->br();

        $this->activeFramework = $this->getFrameworkOption();
        $this->activePackages = $this->getPackageOptions();

        $this->showEnteredOptions();
        if ($this->confirmOptions() === false) {
            return false;
        }

        $this->buildProject();
        $this->cli->br()->green('Yhea, success')->br();
    }

    private function setupDependencies(bool $dryRun)
    {
        $composerManager = new ComposerManager($this->cli, $dryRun);
        $this->packageManager = new PackageManager($this->filesystem);
        $this->frameworkManager = new FrameworkManager($this->filesystem);

        $this->frameworkManager->addFramework(new Laravel54Framework($composerManager));
        $this->frameworkManager->addFramework(new LaravelLumen54Framework($composerManager));

        $this->packageManager->addPackage(new BehatPackage($composerManager));
    }

    private function getFrameworkOption()
    {
        $frameworkQuestion = $this->cli->radio('Choose your framework:', $this->frameworkManager->getFrameworkNames());
        return $this->frameworkManager->load($frameworkQuestion->prompt());
    }

    private function getPackageOptions()
    {
        $packagesQuestion = $this->cli->checkboxes('Choose your packages', $this->packageManager->getPackageNames());
        return $this->packageManager->load($packagesQuestion->prompt());
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

        foreach($this->activePackages as $key => $package) {
            $this->packageManager->install($package);
            $this->packageManager->tidyUp($package);
        }
    }
}