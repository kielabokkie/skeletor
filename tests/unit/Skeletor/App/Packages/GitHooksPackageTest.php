<?php
namespace Skeletor\App\Packages;


use Codeception\Util\Stub;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Skeletor\Manager\ComposerManager;
use Skeletor\Packages\GitHooksPackage;

class GitHooksPackageTest extends \Codeception\Test\Unit
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
        $mountmanager = Stub::make(
            MountManager::class,
            [
            ]
        );
        $options = [];

        $this->gitHooksPackage = new GitHooksPackage($composerManager, $projectFilesystem, $mountmanager, $options);
    }

    protected function _after()
    {
    }

    // tests
    public function testGetPackage()
    {
        $this->assertInternalType('string', $this->gitHooksPackage->getPackage());
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