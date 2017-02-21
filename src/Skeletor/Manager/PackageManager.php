<?php
namespace Skeletor\Manager;

use Skeletor\Api\PackagistApi;
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

    /**
     * @var Packagist
     */
    protected $packagistApi;

    public function __construct(ComposerManager $composerManager, Packages $packages, PackagistApi $packagistApi)
    {
        $this->packages = $packages;
        $this->packagistApi = $packagistApi;
        $this->composerManager = $composerManager;
    }

    public function getPackageNames()
    {
        return array_keys($this->packages->getPackages());
    }

    public function load(array $packages)
    {
        $activePackages = [];
        $availablePackages = $this->packages->getPackages();

        foreach ($packages as $key) {
            $activePackages[$key] = $availablePackages[$key];
        }

        return $activePackages;
    }

    public function addDefaultPackages(array $packages)
    {
        return array_merge($packages, $this->packages->getDefaultPackages());
    }

    public function getVersionsPackages(array $packages)
    {
        return $this->packagistApi->getAvailablePackasgeVersions($packages);
    }

    public function install(array $activePackages)
    {
        foreach($activePackages as $key => $package)
        {
            $command = $this->composerManager->preparePackageCommand($package['slug'], $package['version']);
            $this->composerManager->runCommand($command);
        }
    }
}