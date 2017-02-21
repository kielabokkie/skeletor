<?php
namespace Skeletor\Manager;

use Skeletor\Packages\Exception\FailedToLoadPackageException;
use League\Flysystem\Filesystem;
use Skeletor\Packages\Package;

class PackageManager
{
    /**
     * @var array with packages
     */
    protected $packages;

    /**
     * @var instance of the filesystem
     */
    protected $fileSystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->filesystem = $fileSystem;
    }

    public function addPackage(Package $package)
    {
        $this->packages[] = $package;
    }

    public function getPackageNames()
    {
        return array_map(function($package) {
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

    public function install($package)
    {
        $package->install();
    }

    public function tidyUp($package)
    {
        $package->tidyUp($this->filesystem);
    }
}