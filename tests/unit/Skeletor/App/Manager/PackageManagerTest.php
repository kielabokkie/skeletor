<?php
namespace Skeletor\App\Manager;

use Codeception\Util\Stub;
use League\CLImate\CLImate;
use Skeletor\Manager\PackageManager;
use Skeletor\Packages\GitHooksPackage;

class PackageManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $packageManager;

    protected function _before()
    {
        $cli = Stub::make(
            CLImate::class,
            [
            ]
        );
        $defaultPackage = Stub::make(
            GitHooksPackage::class,
            [
                'getPackage' => 'pixelfusion/git-hooks',
                'getName' => 'PixelFusion Git Hooks'
            ]
        );

        $options = [];
        $this->packageManager = new PackageManager($cli, $options);

        //Load one framework
        $this->packageManager->setDefaultPackages([$defaultPackage]);
    }

    protected function _after()
    {
    }

    // tests
    public function testGetInstallablePackageNames()
    {
        $this->assertInternalType('array', $this->packageManager->getInstallablePackageNames());
    }

    public function testLoadPackages()
    {
        $this->assertInternalType('array', $this->packageManager->load(['PixelFusion Git Hooks']));
    }

    public function testShowPackagesTable()
    {
        $package = Stub::make(
            GitHooksPackage::class,
            [
                'getPackage' => 'pixelfusion/git-hooks',
                'getName' => 'PixelFusion Git Hooks'
            ]
        );

        $this->assertInternalType('array', $this->packageManager->showPackagesTable([$package]));
    }
}