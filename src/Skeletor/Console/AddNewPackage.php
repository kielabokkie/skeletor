<?php
namespace Skeletor\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddNewPackage extends Command
{
    protected $cli;
    protected $packagistApi;
    protected $configurator;
    protected $packageManager;
    protected $skeletorFilesystem;

    protected function configure()
    {
        $this->setName('package:add')
            ->setDescription('Add new package')
            ->addArgument('name', InputArgument::REQUIRED, 'Package name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApplication()->registerServices();
        $this->setupCommand();
        $package = $input->getArgument('name');
        $this->cli->br()->yellow(sprintf('Skeletor - add new package, searching for %s', $package))->br();

        $packageOptions = $this->packagistApi->searchPackage($package);
        if (empty($packageOptions)) {
            $this->cli->br()->red('package not found');
            return;
        }

        $packageInfo['slug'] = $this->packageManager->specifyPackage($packageOptions);
        $packageInfo = $this->buildPackageInfo($packageInfo);
        if (in_array($packageInfo['name'], $this->packageManager->getAllPackageNames())) {
            $this->cli->br()->red('package already installed');
            return;
        }

        $this->addPackageToConfig($packageInfo);
        $this->makePackageClass($packageInfo);
    }

    protected function setupCommand()
    {
        $this->cli = $this->getApplication()->getCli();
        $this->packagistApi = $this->getApplication()->getPackagistApi();
        $this->configurator = $this->getApplication()->getConfigurator();
        $this->packageManager = $this->getApplication()->getPackageManager();

        $this->skeletorFilesystem = $this->getApplication()->getSkeletorFilesystem();
        $this->packageManager->setPackages($this->getApplication()->getPackages());
        $this->packageManager->setDefaultPackages($this->getApplication()->getDefaultPackages());
    }

    /**
     * @param array $packageInfo
     * @return array
     */
    protected function buildPackageInfo(array $packageInfo)
    {
        $packageName = preg_replace('//|-/', ' ', $packageInfo['slug']);
        $packageName = ucwords($packageName);
        $packageInfo['name'] = $packageName;

        $packageName = preg_replace('/\s+/', '', $packageName);
        $packageInfo['class'] = $packageName.'Package';

        return $packageInfo;
    }

    /**
     * @param array $packageInfo
     */
    protected function addPackageToConfig(array $packageInfo)
    {
        $config = $this->configurator->getConfig();
        $config['packages'][] = $packageInfo['class'];
        $this->configurator->storeConfig($config);
    }

    /**
     * @param array $packageInfo
     */
    protected function makePackageClass(array $packageInfo)
    {
        //Read stub
        $stub = $this->skeletorFilesystem->read('Templates/stubs/package.stub');

        //Replace stub dummy data
        foreach ($packageInfo as $key => $info) {
            $stub = str_replace($key.'Dummy', $info, $stub);
        }

        //Put final class in Skeletor
        $this->skeletorFilesystem->put(
            'Packages/'.$packageInfo['class'].'.php',
            $stub
        );
    }
}