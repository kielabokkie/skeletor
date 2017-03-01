<?php
namespace Skeletor\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetPackageVersions extends Command
{
    protected function configure()
    {
        $this->setName('package:show')
            ->setDescription('Shows the available package versions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApplication()->registerServices();

        $cli = $this->getApplication()->getCli();
        $packagistApi = $this->getApplication()->getPackagistApi();

        $packageManager = $this->getApplication()->getPackageManager();
        $packageManager->setPackages($this->getApplication()->getPackages());

        $packages = $packageManager->getPackages();
        $packageVersions = $packagistApi->getAvailablePackasgeVersions($packages);

        $cli->dump($packageVersions);
    }
}