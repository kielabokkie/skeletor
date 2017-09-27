<?php
namespace Skeletor\App\Packages;

use Codeception\Util\Stub;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Skeletor\Manager\ComposerManager;
use Skeletor\Manager\RunManager;
use Skeletor\Packages\PixelfusionGitHooksPackage;

class PixelfusionGitHooksPackageTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $gitHooksPackage;

    protected function _before()
    {
        $composerManager = Stub::make(
            ComposerManager::class,
            [
            ]
        );
        $projectFilesystem = Stub::make(
            Filesystem::class,
            [
            ]
        );
        $mountManager = Stub::make(
            MountManager::class,
            [
            ]
        );
        $runManager = Stub::make(
            RunManager::class,
            [
            ]
        );
        $options = [];

        $this->gitHooksPackage = new PixelfusionGitHooksPackage($composerManager, $projectFilesystem, $mountManager, $runManager, $options);
    }

    protected function _after()
    {
    }

    // tests
    public function testGetPackage()
    {
        $this->assertInternalType('string', $this->gitHooksPackage->getInstallSlug());
    }

    public function testGetName()
    {
        $this->assertInternalType('string', $this->gitHooksPackage->getName());
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->gitHooksPackage->getVersion());
    }

    public function testGetOptions()
    {
        $this->assertInternalType('string', $this->gitHooksPackage->getPackageOptions());
    }
}
