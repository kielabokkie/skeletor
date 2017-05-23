<?php
namespace Skeletor\Console;

use Symfony\Component\Console\Command\Command;

abstract class SkeletorCommand extends Command
{
    protected $cli;
    protected $packagistApi;
    protected $configurator;
    protected $packageManager;
    protected $providerManager;
    protected $frameworkManager;
    protected $skeletorFilesystem;

    protected function setupCommand($dryRun = false)
    {
        $app = $this->getApplication();
        $this->getApplication()->registerServices($dryRun);

        $this->cli = $app->getCli();
        $this->packagistApi = $app->getPackagistApi();
        $this->configurator = $app->getConfigurator();
        $this->skeletorFilesystem = $app->getSkeletorFilesystem();

        $this->frameworkManager = $app->getFrameworkManager();
        $this->packageManager = $app->getPackageManager();
        $this->providerManager = $app->getProviderManager();

        $this->frameworkManager->setFrameworks($app->getFrameworks());
        $this->packageManager->setPackages($app->getPackages());
        $this->packageManager->setDefaultPackages($app->getDefaultPackages());
    }
}