<?php
namespace Skeletor\App\Frameworks;

use Codeception\Util\Stub;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Skeletor\Frameworks\Laravel54Framework;
use Skeletor\Manager\ComposerManager;
use Skeletor\Manager\RunManager;

class Laravel54FrameworkTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $laravel54Framework;

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

        $this->laravel54Framework = new Laravel54Framework($composerManager, $projectFilesystem, $mountManager, $runManager, $options);
    }

    protected function _after()
    {
    }

    // tests
    public function testGetFramework()
    {
        $this->assertInternalType('string', $this->laravel54Framework->getInstallSlug());
    }

    public function testGetName()
    {
        $this->assertInternalType('string', $this->laravel54Framework->getName());
    }

    public function testGetVersion()
    {
        $this->assertInternalType('string', $this->laravel54Framework->getVersion());
    }

    public function testGetPath()
    {
        $this->assertInternalType('string', $this->laravel54Framework->getPath('tests'));
    }
}
