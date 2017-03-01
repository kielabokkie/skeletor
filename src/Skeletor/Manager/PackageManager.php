<?php
namespace Skeletor\Manager;

use Skeletor\Packages\Package;
use Skeletor\Frameworks\Framework;

class PackageManager extends Manager
{
    /**
     * @var array with packages
     */
    protected $packages = array();

    /**
     * @var array with default packages
     */
    protected $defaultPackages = array();

    public function setPackages(array $packages)
    {
        $this->packages = $packages;
    }

    public function setDefaultPackages(array $defaultPackages)
    {
        $this->defaultPackages = $defaultPackages;
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

    public function getPackageOptions()
    {
        $packagesQuestion = $this->cli->checkboxes('Choose your packages', $this->getInstallablePackageNames());
        return $this->load($packagesQuestion->prompt());
    }

    public function specifyPackagesVersions(array $packages)
    {
        foreach ($packages as $key => $package)
        {
            $input = $this->cli->input(sprintf('%s version [%s]:', $package->getName(), $package->getVersion() ));
            $version = $input->prompt();

            if(!empty($version)) {
                $package->setVersion($version);
            }
        }
    }

    public function install(Package $package)
    {
        $package->install();
    }

    public function configure(Package $package, Framework $activeFramework)
    {
        if(!$this->options['dryRun']) {
            $package->configure($activeFramework);
        }
    }
}