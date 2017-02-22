<?php
namespace Skeletor\Console\Command;

use Skeletor\Frameworks\Laravel54Framework;
use Skeletor\Frameworks\LaravelLumen54Framework;
use Skeletor\Packages\JsonBehatExtsionPackage;
use Skeletor\Packages\BehatPackage;
use Skeletor\Manager\PackageManager;
use Skeletor\Manager\ComposerManager;
use Skeletor\Manager\FrameworkManager;
use League\CLImate\CLImate;
use League\Flysystem\Filesystem;
use Skeletor\Packages\GitHooksPackage;
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
        $this->setupDependencies($dryRun);

        $this->cli->dump(getcwd());
        $this->cli->br()->yellow(sprintf('Skeletor - %s project creator', implode(" / ", $this->frameworkManager->getFrameworkNames()) ))->br();
        $this->activeFramework = $this->getFrameworkOption();
        $this->activePackages = $this->getPackageOptions();

        if ($this->confirmOptions("Specify package versions?")) {
            $this->specifyPackagesVersions();
        }

        $this->showEnteredOptions();
        if (!$this->confirmOptions()) {
            return false;
        }

        $this->activePackages = $this->packageManager->mergeSelectedAndDefaultPackages($this->activePackages);
        $this->buildProject();
        $this->cli->br()->green('Yhea, success')->br();
    }

    private function setupDependencies(bool $dryRun)
    {
        $composerManager = new ComposerManager($this->cli, $dryRun);
        $this->packageManager = new PackageManager($this->filesystem, $dryRun);
        $this->frameworkManager = new FrameworkManager($this->filesystem, $dryRun);

        $this->frameworkManager->addFramework(new Laravel54Framework($composerManager));
        $this->frameworkManager->addFramework(new LaravelLumen54Framework($composerManager));

        $this->packageManager->addPackage(new BehatPackage($composerManager));
        $this->packageManager->addPackage(new JsonBehatExtsionPackage($composerManager));
        $this->packageManager->addDefaultPackage(new GitHooksPackage($composerManager));
    }

    private function getFrameworkOption()
    {
        $frameworkQuestion = $this->cli->radio('Choose your framework:', $this->frameworkManager->getFrameworkNames());
        return $this->frameworkManager->load($frameworkQuestion->prompt());
    }

    private function getPackageOptions()
    {
        $packagesQuestion = $this->cli->checkboxes('Choose your packages', $this->packageManager->getInstallablePackageNames());
        return $this->packageManager->load($packagesQuestion->prompt());
    }

    private function specifyPackagesVersions()
    {
        foreach ($this->activePackages as $key => $package)
        {
            $input = $this->cli->input(sprintf('%s version [%s]:', $package->getName(), $package->getVersion() ));
            $version = $input->prompt();

            if(!empty($version)) {
                $package->setVersion($version);
            }
        }
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