<?php
namespace Skeletor\Console\Command;

use Skeletor\Frameworks\Framework;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Skeletor\Console\Exception\FailedFilesystem;
use Symfony\Component\Console\Input\InputArgument;
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

    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new Laravel/Lumen project skeleton')
            ->addArgument('name', InputArgument::REQUIRED, 'Project name')
            ->addOption('dryrun', null, InputOption::VALUE_NONE, 'Dryrun the install', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dryRun = $input->getOption('dryrun');
        $name = strtolower($input->getArgument('name'));
        $this->setupFolder($name);
        $this->getApplication()->registrateServices($dryRun);
        $this->setupCommand();

        $this->cli->br()->yellow(sprintf('Skeletor - %s project creator', implode(" / ", $this->frameworkManager->getFrameworkNames())))->br();
        $activeFramework = $this->frameworkManager->getFrameworkOption();
        $activePackages = $this->packageManager->getPackageOptions();

        $this->cli->br()->dump($this->getApplication()->options);

        $this->cli->br()->dump($this->getApplication()->container->get('SkeletorFilesystem')->getSize(
            $this->getApplication()->options['templatePath'].'/JsonBehatExtensionPackage/FeatureContext.php'
        ));

        if ($this->confirmOptions("Specify package versions?")) {
            $this->packageManager->specifyPackagesVersions($activePackages);
        }

        $this->showEnteredOptions($activeFramework, $activePackages);
        if (!$this->confirmOptions()) {
            return false;
        }

        $activePackages = $this->packageManager->mergeSelectedAndDefaultPackages($activePackages);
        $this->buildProject($activeFramework, $activePackages);
        $this->cli->br()->green('Yhea, success')->br();
    }

    private function setupCommand()
    {
        $this->frameworkManager = $this->getApplication()->getFrameworkManager();
        $this->packageManager = $this->getApplication()->getPackageManager();
        $this->cli = $this->getApplication()->getCli();

        $this->frameworkManager->setFrameworks($this->getApplication()->getFrameworks());
        $this->packageManager->setPackages($this->getApplication()->getPackages());
        $this->packageManager->setDefaultPackages($this->getApplication()->getDefaultPackages());
    }

    private function setupFolder(string $name)
    {
        if((is_dir($name)  || is_file($directory)) && $directory != getcwd()) {
            throw new FailedFilesystem(sprintf('Failed to make directory %s already exists', $name));
        }

        if(!mkdir($name)) {
            throw new FailedFilesystem('Failed make directory for: ' . $name);
        };

        if(!chdir($name)) {
            throw new FailedFilesystem('Failed change directory to: ' . $name);
        }
    }

    private function showEnteredOptions(Framework $activeFramework, array $activePackages)
    {
        $padding = $this->cli->padding(20);
        $this->cli->br()->yellow('Project setup:');
        $padding->label('Framework')->result($activeFramework->getName());
        $padding->label('Version')->result($activeFramework->getVersion());
        $this->cli->br()->yellow('Packages:');
        $this->cli->table($this->packageManager->showPackagesTable($activePackages));
    }

    private function confirmOptions($text = "Continue?")
    {
        $input = $this->cli->confirm($text);
        if ($input->confirmed()) {
            return true;
        }
        return false;
    }

    private function buildProject(Framework $activeFramework, array $activePackages)
    {
        $this->cli->br()->green('Building..');
        $this->frameworkManager->install($activeFramework);
        $this->frameworkManager->configure($activeFramework);

        foreach($activePackages as $key => $package) {
            $this->packageManager->install($package);
            $this->packageManager->configure($package, $activeFramework);
        }
    }
}