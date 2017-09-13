<?php
namespace Skeletor\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetPackageVersions extends SkeletorCommand
{
    protected function configure()
    {
        $this->setName('package:show')
            ->setDescription('Shows the available package versions')
            ->addOption('no-ansi', null, InputOption::VALUE_NONE, 'Disable ANSI output', null);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ansi = $input->getOption('no-ansi');
        $this->setupCommand();

        $packageVersions = $this->packagistApi->getAvailablePackageVersions($this->packageManager->getPackages());
        $this->skeletorFilesystem->put('Tmp/PackageVersions.json', json_encode($packageVersions));

        if(!$ansi) {
            $cli = $this->getApplication()->getCli();
            $cli->br()->dump($packageVersions);
        }
    }
}
