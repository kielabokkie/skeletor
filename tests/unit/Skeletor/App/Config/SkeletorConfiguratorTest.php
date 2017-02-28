<?php
namespace Skeletor\App\Config;

class SkeletorConfiguratorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $configurator;

    protected function _before()
    {
        $this->configurator = new SkeletorConfigurator();
    }

    protected function _after()
    {
    }

    // tests
    public function testGetFrameworks()
    {
        $this->assertInternalType('array', $this->configurator->getFrameworks());
    }

    public function testGetPackages()
    {
        $this->assertInternalType('array', $this->configurator->getPackages());
    }

    public function testGetDefaultPackages()
    {
        $this->assertInternalType('array', $this->configurator->getDefaultPackages());
    }

    public function testGetName()
    {
        $this->assertInternalType('string', $this->configurator->getName());
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->configurator->getVersion());
    }

    public function testGetFullConfig()
    {
        $this->assertInternalType('array', $this->configurator->getConfig());
        $this->assertArrayHasKey('config', $this->configurator->getConfig());
        $this->assertArrayHasKey('frameworks', $this->configurator->getConfig());
        $this->assertArrayHasKey('packages', $this->configurator->getConfig());
        $this->assertArrayHasKey('defaultPackages', $this->configurator->getConfig());
    }
}