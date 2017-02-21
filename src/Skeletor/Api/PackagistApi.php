<?php
namespace Skeletor\Api;

use Skeletor\Api\Exception\FailedToLoadPackageVersion;

class PackagistApi extends Api
{
    public function getAvailablePackasgeVersions(array $packages)
    {
        $packageVersions = [];
        foreach($packages as $key => $package)
        {
            $packageVersions[$key] = $this->getVersionsPackage($package['slug']);
        }
        return $packageVersions;
    }

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

    public function buildUrl($packageSlug)
    {
        return sprintf('https://packagist.org/p/%s.json', $packageSlug);
    }
}