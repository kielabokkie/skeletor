<?php
namespace Skeletor\Manager;

use Skeletor\Packages\Packages;

class PackageManager
{
    /**
     * @var instance of ComposerManager
     */
    protected $composerManager;

    /**
     * @var instance of Packages
     */
    protected $packages;

    public function __construct(ComposerManager $composerManager, Packages $packages)
    {
        $this->packages = $packages;
        $this->composerManager = $composerManager;
    }

    public function getPackageNames()
    {
        return array_keys($this->packages->getPackages());
    }

    public function load($packages)
    {
        $activePackages = [];
        $availablePackages = $this->packages->getPackages();

        foreach ($packages as $key) {
            $activePackages[$key] = $availablePackages[$key];
        }

        return array_merge($activePackages, $this->packages->getDefaultPackages());
    }

    public function install($activePackages)
    {
        foreach($activePackages as $key => $package)
        {
            $command = $this->composerManager->preparePackageCommand($package['slug'], $package['version']);
            $this->composerManager->runCommand($command);
        }
    }
}