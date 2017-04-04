<?php
namespace Skeletor\Console;

use Skeletor\Frameworks\Framework;
use Symfony\Component\Process\Process;
use Skeletor\Exceptions\FailedFilesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateProjectCommand extends SkeletorCommand
{
    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new Laravel/Lumen project skeleton')
            ->addArgument('name', InputArgument::REQUIRED, 'Project name')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dryrun the install', null);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dryRun = $input->getOption('dry-run');
        $name = strtolower($input->getArgument('name'));
        if (!$dryRun) {
            $this->setupFolder($name);
        }

        $this->setupCommand($dryRun);

        //Start process in the background
        $process = new Process('skeletor package:show --no-ansi');
        $process->setTimeout(7200);
        $process->start();

        $this->cli->br()->yellow(sprintf('Skeletor - %s project creator', implode(" / ", $this->frameworkManager->getFrameworkNames())))->br();
        $activeFramework = $this->frameworkManager->getFrameworkOption();
        $activePackages = $this->packageManager->getPackageOptions();

        if ($this->confirmOptions("Specify package versions?")) {
            $process->wait(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    $this->cli->red('ERR > ');
                } else {
                    $this->cli->green('Fetching versions...');
                }
            });
            $this->packageManager->specifyPackagesVersions($activePackages);
        }

        $this->showEnteredOptions($activeFramework, $activePackages);
        if (!$this->confirmOptions()) {
            return false;
        }

        $activePackages = $this->packageManager->mergePackagesWithDefault($activePackages);
        $this->buildProject($activeFramework, $activePackages);
        $this->cli->br()->green('Yhea, success')->br();
    }

    /**
     * @param string $name
     */
    private function setupFolder(string $name)
    {
        if ((is_dir($name)  || is_file($name)) && $name != getcwd()) {
            throw new FailedFilesystem(sprintf('Failed to make directory %s already exists', $name));
        }

        if (!mkdir($name)) {
            throw new FailedFilesystem('Failed make directory for: ' . $name);
        };

        if (!chdir($name)) {
            throw new FailedFilesystem('Failed change directory to: ' . $name);
        }
    }

    /**
     * @param Framework $activeFramework
     * @param array $activePackages
     */
    private function showEnteredOptions(Framework $activeFramework, array $activePackages)
    {
        $padding = $this->cli->padding(20);
        $this->cli->br()->yellow('Project setup:');
        $padding->label('Framework')->result($activeFramework->getName());
        $padding->label('Version')->result($activeFramework->getVersion());
        $this->cli->br()->yellow('Packages:');
        $this->cli->table($this->packageManager->showPackagesTable($activePackages));
    }

    /**
     * @param string $text
     * @return bool
     */
    private function confirmOptions($text = "Continue?")
    {
        $input = $this->cli->confirm($text);
        return $input->confirmed();
    }

    /**
     * @param Framework $activeFramework
     * @param array $activePackages
     */
    private function buildProject(Framework $activeFramework, array $activePackages)
    {
        $this->cli->br()->green('Building..');
        $this->frameworkManager->install($activeFramework);
        $this->frameworkManager->configure($activeFramework);

        foreach ($activePackages as $key => $package) {
            $this->packageManager->install($package);

            if ($package instanceof ConfigurablePackageInterface) {
                $this->packageManager->configure($package, $activeFramework);
            }
        }
    }
}
