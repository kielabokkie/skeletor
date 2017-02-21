<?php
namespace Skeletor\Manager;

use League\Flysystem\Filesystem;
use Skeletor\Packages\Package;

class PackageManager
{
    /**
     * @var array with packages
     */
    protected $packages;

    /**
     * @var array with default packages
     */
    protected $defaultPackages;

    /**
     * @var instance of the filesystem
     */
    protected $fileSystem;
    protected $dryRun;

    public function __construct(Filesystem $fileSystem, bool $dryRun)
    {
        $this->filesystem = $fileSystem;
        $this->dryRun = $dryRun;
    }

    public function addPackage(Package $package)
    {
        $this->packages[] = $package;
    }

    public function addDefaultPackage(Package $package)
    {
        $this->defaultPackages[] = $package;
    }

    public function getInstallablePackageNames()
    {
        return array_map(function(Package $package) {
            return $package->getName();
        }, $this->packages);
    }

    public function load(array $names)
    {
        $activePackages = [];
        foreach($this->packages as $key => $package) {
            if( in_array($package->getName(), $names) ) {
                $activePackages[] = $package;
            }
        }
        return $activePackages;
    }

    public function showPackagesTable(array $packages)
    {
        return array_map(function($package) {
            return ['name' => $package->getName(), 'version' => $package->getVersion()];
        }, $packages);
    }

    public function mergeSelectedAndDefaultPackages(array $selectedPacakges)
    {
        return array_merge($selectedPacakges, $this->defaultPackages);
    }

    public function install(Package $package)
    {
        $package->install();
    }

    public function tidyUp(Package $package)
    {
        if(!$this->dryRun) {
            $package->tidyUp($this->fileSystem);
        }
    }
}