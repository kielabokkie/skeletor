<?php
namespace Skeletor\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetPackageVersions extends Command
{
    protected function configure()
    {
        $this->setName('package:show')
            ->setDescription('Shows the available package versions')
            ->addOption('no-ansi', null, InputOption::VALUE_NONE, 'Disable ANSI output', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ansi = $input->getOption('no-ansi');
        $this->getApplication()->registerServices();
        $packagistApi = $this->getApplication()->getPackagistApi();

        $packageManager = $this->getApplication()->getPackageManager();
        $packageManager->setPackages($this->getApplication()->getPackages());

        $packages = $packageManager->getPackages();
        $packageVersions = $packagistApi->getAvailablePackasgeVersions($packages);

        $skeletorFilesystem = $this->getApplication()->getSkeletorFilesystem();
        $skeletorFilesystem->put('Tmp/PackageVersions.json', json_encode($packageVersions));

        if(!$ansi) {
            $cli = $this->getApplication()->getCli();
            $cli->br()->dump($packageVersions);
        }
    }
}