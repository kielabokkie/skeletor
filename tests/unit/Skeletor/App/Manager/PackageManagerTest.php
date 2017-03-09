<?php
namespace Skeletor\App\Manager;

use Codeception\Util\Stub;
use League\CLImate\CLImate;
use League\Flysystem\Filesystem;
use Skeletor\Manager\PackageManager;
use Skeletor\Packages\GitHooksPackage;
use Skeletor\Packages\PixelfusionGitHooksPackage;

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
        $skeletorFilesystem = Stub::make(
            Filesystem::class,
            [
                'has' => true,
                'read' => '{"Behat Behat":["v3.3.0","v3.2.3","v3.2.2","v3.2.1","v3.2.0rc2","v3.2.0rc1","v3.2.0","v3.1.0rc2","v3.1.0rc1","v3.1.0"]}',
            ]
        );
        $defaultPackage = Stub::make(
            PixelfusionGitHooksPackage::class,
            [
                'getPackage' => 'pixelfusion/git-hooks',
                'getName' => 'PixelFusion Git Hooks'
            ]
        );

        $options = [];
        $this->packageManager = new PackageManager($cli, $skeletorFilesystem, $options);

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

    public function testGetAvailablePackageVersions()
    {
        $this->assertInternalType('array', $this->packageManager->getAvailablePackageVersions());
    }

    public function testShowPackagesTable()
    {
        $package = Stub::make(
            PixelfusionGitHooksPackage::class,
            [
                'getPackage' => 'pixelfusion/git-hooks',
                'getName' => 'PixelFusion Git Hooks'
            ]
        );

        $this->assertInternalType('array', $this->packageManager->showPackagesTable([$package]));
    }
}