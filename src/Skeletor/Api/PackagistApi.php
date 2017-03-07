<?php
namespace Skeletor\Api;

use Skeletor\Exceptions\FailedToLoadPackageException;
use Skeletor\Exceptions\FailedToLoadPackageVersion;

class PackagistApi
{
    /**
     * @param array $packages
     * @return array
     */
    public function getAvailablePackasgeVersions(array $packages)
    {
        $packageVersions = [];
        foreach($packages as $key => $package)
        {
            $packageVersions[$package->getName()] = $this->getVersionsPackage($package->getInstallSlug());
        }
        return $packageVersions;
    }

    /**
     * @param string $packageSlug
     * @return array
     */
    public function getVersionsPackage(string $packageSlug)
    {
        $data = file_get_contents($this->buildUrl($packageSlug));

        if(!$data){
            throw New FailedToLoadPackageVersion('Couldnt find version for ' . $packageSlug);
        }

        $packageData = json_decode($data, true);
        $versions = array_keys($packageData['packages'][$packageSlug]);

        // Flip the array and return versions
        return array_reverse($versions);
    }

    /**
     * @param string $package
     * @return array
     */
    public function searchPackage(string $package)
    {
        $data = file_get_contents($this->buildSearchUrl($package));
        $data = json_decode($data, true);

        return array_map(function($result) {
            return $result['name'];
        }, $data['results']);
    }

    /**
     * @param string $packageSlug
     * @return string
     */
    public function buildUrl(string $packageSlug)
    {
        return sprintf('https://packagist.org/p/%s.json', $packageSlug);
    }

    /**
     * @param string $packageSlug
     * @return string
     */
    public function buildSearchUrl(string $packageSlug)
    {
        return sprintf('https://packagist.org/search.json?q=%s', $packageSlug);
    }
}