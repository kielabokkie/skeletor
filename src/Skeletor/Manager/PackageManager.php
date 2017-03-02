<?php
namespace Skeletor\Manager;

use Skeletor\Exceptions\FailedToLoadPackageException;
use Skeletor\Packages\Package;
use Skeletor\Frameworks\Framework;
use Symfony\Component\Console\Exception\RuntimeException;

class PackageManager extends Manager
{
    protected $defaultPackages = array();
    protected $packages = array();

    public function setPackages(array $packages)
    {
        $this->packages = $packages;
    }

    public function getPackages()
    {
        return $this->packages;
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

    public function getAvailablePackageVersions()
    {
        if( !$this->skeletorFilesystem->has('Tmp/PackageVersions.json') ){
            throw new FailedToLoadPackageException('Could not load package versions');
        }

        $versions = $this->skeletorFilesystem->read('Tmp/PackageVersions.json');
        return json_decode($versions, true);
    }

    public function specifyPackagesVersions(array $packages)
    {
        $versions = $this->getAvailablePackageVersions();
        foreach ($packages as $key => $package)
        {
            $this->cli->br()->yellow(sprintf('Available %s versions: %s', $package->getName(), implode(', ', $versions[$package->getName()])));
            $input = $this->cli->input(sprintf('%s version [%s]:', $package->getName(), $package->getVersion() ));
            $versions[$package->getName()][] = '';

            $input->accept($versions[$package->getName()]);
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