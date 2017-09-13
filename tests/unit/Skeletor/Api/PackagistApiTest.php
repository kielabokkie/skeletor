<?php
namespace Skeletor\Api;


class PackagistApiTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testAvailablePackagesVersionsReturnsEmptyArrayIfThereAreNoPackages()
    {
        $api = new PackagistApi();
        $versions = $api->getAvailablePackageVersions([]);

        $this->assertInternalType('array', $versions);
        $this->assertCount(0, $versions);
    }

    public function testSearchPackage()
    {
        $api = new PackagistApi();
        $this->assertInternalType('array', $api->searchPackage('behat'));
    }

    public function testBuildSearchUrl()
    {
        $api = new PackagistApi();
        $package = 'behat';
        $this->assertStringEndsWith($package, $api->buildSearchUrl($package));
    }
}
