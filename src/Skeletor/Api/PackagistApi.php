<?php
namespace Skeletor\Api;

use Skeletor\Exceptions\FailedToLoadPackageVersion;

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
            $packageVersions[$key] = $this->getVersionsPackage($package['slug']);
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

        $packageData = $this->jsonDecode($data);
        $versions = array_keys($packageData['packages'][$packageSlug]);

        // Flip the array and return 10 latest versions
        return array_slice( array_reverse($versions), 0, 10);
    }

    /**
     * @param string $packageSlug
     * @return string
     */
    public function buildUrl(string $packageSlug)
    {
        return sprintf('https://packagist.org/p/%s.json', $packageSlug);
    }
}