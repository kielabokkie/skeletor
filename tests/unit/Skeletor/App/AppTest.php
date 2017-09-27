<?php
namespace Skeletor\App;

use Codeception\Util\Stub;
use League\CLImate\CLImate;
use League\Container\Container;
use Skeletor\App\Config\SkeletorConfigurator;
use Skeletor\Manager\FrameworkManager;
use Skeletor\Manager\PackageManager;
use Skeletor\Manager\RunManager;

class AppTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $app;

    protected function _before()
    {
        $container = new Container();
        $config = Stub::make(
            SkeletorConfigurator::class,
            [
                'getName' => 'Test CLI',
                'getVersion' => '0.0.1',
                'getFrameworks' => ['Laravel54Framework'],
                'getPackages' => ['BehatBehatPackage'],
                'getDefaultPackages' => ['PixelfusionGitHooksPackage'],
                'getManagers' => ['ComposerManager', 'FrameworkManager', 'PackageManager', 'RunManager']
            ]
        );
        $this->app = new App($config, $container, $config->getName(), $config->getVersion());
        $this->app->registerServices(true);
    }

    protected function _after()
    {
    }

    public function testGetFrameworks()
    {
        $this->assertInternalType('array', $this->app->getFrameworks());
    }

    public function testGetPackages()
    {
        $this->assertInternalType('array', $this->app->getPackages());
    }

    public function testGetDefaultPackages()
    {
        $this->assertInternalType('array', $this->app->getDefaultPackages());
    }

    public function testFrameworkManagerInstanceSetup()
    {
        $this->assertInstanceOf(FrameworkManager::class, $this->app->getFrameworkManager());
    }

    public function testPackageManagerInstanceSetup()
    {
        $this->assertInstanceOf(PackageManager::class, $this->app->getPackageManager());
    }

    public function testCliInstanceSetup()
    {
        $this->assertInstanceOf(CLImate::class, $this->app->getCli());
    }
}
