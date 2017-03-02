<?php
namespace Skeletor\Api;

use Skeletor\Exceptions\FailedToLoadPackageException;

class PackagistApi extends Api
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
            throw New FailedToLoadPackageException('Couldnt find version for ' . $packageSlug);
        }

        $packageData = $this->jsonDecode($data);
        $versions = array_keys($packageData['packages'][$packageSlug]);

        // Flip the array and return versions
        return array_reverse($versions);
    }

    /**
     * @param $packageSlug
     * @return string
     */
    public function buildUrl($packageSlug)
    {
        return sprintf('https://packagist.org/p/%s.json', $packageSlug);
    }
}