<?php
namespace Skeletor\Manager;

use Skeletor\Packages\Package;
use Skeletor\Frameworks\Framework;
use Skeletor\Exceptions\FailedToLoadPackageException;

class PackageManager extends Manager
{
    protected $defaultPackages = array();
    protected $packages = array();

    /**
     * @param array $packages
     */
    public function setPackages(array $packages)
    {
        $this->packages = $packages;
    }

    /**
     * @return array
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @param array $defaultPackages
     */
    public function setDefaultPackages(array $defaultPackages)
    {
        $this->defaultPackages = $defaultPackages;
    }

    /**
     * @return array
     */
    public function getInstallablePackageSlugs()
    {
        return array_map(function (Package $package) {
            return $package->getInstallSlug();
        }, $this->packages);
    }

    /**
     * @return array
     */
    public function getAllPackageNames()
    {
        return array_map(function (Package $package) {
            return $package->getName();
        }, $this->mergePackagesWithDefault($this->packages));
    }

    /**
     * @param array $slugs
     * @return array
     */
    public function load(array $slugs)
    {
        $activePackages = [];
        foreach ($this->packages as $key => $package) {
            if (in_array($package->getInstallSlug(), $slugs)) {
                $activePackages[] = $package;
            }
        }

        return $activePackages;
    }

    /**
     * @param array $packages
     * @return array
     */
    public function showPackagesTable(array $packages)
    {
        return array_map(function ($package) {
            return ['name' => $package->getInstallSlug(), 'version' => $package->getVersion(false)];
        }, $packages);
    }

    /**
     * @param array $packages
     * @return array
     */
    public function mergePackagesWithDefault(array $packages)
    {
        return array_merge($packages, $this->defaultPackages);
    }

    /**
     * @return array
     */
    public function getPackageOptions()
    {
        $packagesQuestion = $this->cli->br()->checkboxes('Choose your packages', $this->getInstallablePackageSlugs());

        return $this->load($packagesQuestion->prompt());
    }

    /**
     * @return array
     */
    public function getAvailablePackageVersions()
    {
        if ($this->skeletorFilesystem->has('Tmp/PackageVersions.json') === false) {
            throw new FailedToLoadPackageException('Could not load package versions');
        }

        $versions = $this->skeletorFilesystem->read('Tmp/PackageVersions.json');

        return json_decode($versions, true);
    }

    /**
     * @param array $packages
     */
    public function specifyPackagesVersions(array $packages)
    {
        $versions = $this->getAvailablePackageVersions();

        foreach ($packages as $key => $package) {
            $this->cli->br()->yellow(sprintf('Available versions for %s:', $package->getInstallSlug()));
            $this->cli->yellow(implode(', ', $versions[$package->getInstallSlug()]));
            $input = $this->cli->input(sprintf('%s version [%s]:', $package->getInstallSlug(), $package->getVersion(false)));
            $versions[$package->getInstallSlug()][] = '';

            $input->accept($versions[$package->getInstallSlug()]);
            $version = $input->prompt();

            if (!empty($version)) {
                $package->setVersion($version);
            }
        }
    }

    /**
     * @param array $packages
     * @return string with package slug
     */
    public function specifyPackage($packages)
    {
        $packageQuestion = $this->cli->br()->radio('Choose your packages:', $packages);

        return $packageQuestion->prompt();
    }

    /**
     * @param Package $package
     */
    public function install(Package $package)
    {
        $package->install();
    }

    /**
     * @param Package $package
     * @param Framework $activeFramework
     */
    public function configure(Package $package, Framework $activeFramework)
    {
        if (!$this->options['dryRun']) {
            $package->configure($activeFramework);

            // Update .env files
            $this->updateEnvironmentVariables($package);
        }
    }

    /**
     * @param  Package $package
     */
    public function publishConfig(Package $package)
    {
        if (!$this->options['dryRun']) {
            $this->cli->br()->output('Publishing configuration');

            $output = $package->publishConfig();
            $this->cli->output($output);
        }
    }

    /**
     * Update the .env and .env.example files with the environment variables
     * that are specified for the package.
     *
     * @param  Package $package
     */
    private function updateEnvironmentVariables(Package $package)
    {
        // Write the package environment variables to the .env files
        if ($package->hasEnvironmentVariables() === true) {
            $this->cli->br()->output('Writing environment variables');

            $envVariables = $package->getEnvironmentVariables();
            $lines = "\n";

            foreach ($envVariables as $key => $value) {
                $lines .= sprintf("%s=%s\n", $key, $value);
            }

            file_put_contents('.env', $lines, FILE_APPEND);
            file_put_contents('.env.example', $lines, FILE_APPEND);
        }
    }
}
