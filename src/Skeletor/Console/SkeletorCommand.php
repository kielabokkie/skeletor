<?php
namespace Skeletor\Console;

use Symfony\Component\Console\Command\Command;

abstract class SkeletorCommand extends Command
{
    protected $cli;
    protected $packagistApi;
    protected $configurator;
    protected $packageManager;
    protected $frameworkManager;
    protected $skeletorFilesystem;

    protected function setupCommand($dryRun = false)
    {
        $this->getApplication()->registerServices($dryRun);

        $this->cli = $this->getApplication()->getCli();
        $this->packagistApi = $this->getApplication()->getPackagistApi();
        $this->configurator = $this->getApplication()->getConfigurator();
        $this->skeletorFilesystem = $this->getApplication()->getSkeletorFilesystem();

        $this->frameworkManager = $this->getApplication()->getFrameworkManager();
        $this->packageManager = $this->getApplication()->getPackageManager();

        $this->frameworkManager->setFrameworks($this->getApplication()->getFrameworks());
        $this->packageManager->setPackages($this->getApplication()->getPackages());
        $this->packageManager->setDefaultPackages($this->getApplication()->getDefaultPackages());
    }
}