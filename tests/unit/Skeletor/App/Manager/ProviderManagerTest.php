<?php
namespace Skeletor\App\Manager;


use Codeception\Util\Stub;
use League\CLImate\CLImate;
use League\Flysystem\Filesystem;
use Skeletor\Frameworks\Laravel54Framework;
use Skeletor\Manager\ProviderManager;
use Skeletor\Packages\PixelfusionGitHooksPackage;

class ProviderManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $framework;
    protected $package;
    protected $providerManager;

    protected function _before()
    {
        $cli = Stub::make(
            CLImate::class,
            [
                'red' => ''
            ]
        );
        $skeletorFilesystem = Stub::make(
            Filesystem::class,
            [
            ]
        );
        $projectFilesystem = Stub::make(
            Filesystem::class,
            [
            ]
        );

        $this->framework = Stub::make(
            Laravel54Framework::class,
            [
                'getName' => 'Laravel',
                'getVersion' => '5.4',
                'getPath' => 'app/config.php'
            ]
        );

        $this->package = Stub::make(
            PixelfusionGitHooksPackage::class,
            [
                'getInstallSlug' => 'pixelfusion/git-hooks',
                'getName' => 'PixelFusion Git Hooks',
                'getFacade' => 'Pixelfusion@Pixelfusion\Support\Facades\Config',
                'getProvider' => 'Pixelfusion\PathExample\SampleServiceProvider'
            ]
        );

        $options = [];
        $this->providerManager = new ProviderManager($cli, $skeletorFilesystem, $projectFilesystem, $options);
    }

    protected function _after()
    {
    }

    // tests
    public function testGetFacadeClass()
    {
        $assert = sprintf("%s'Pixelfusion' => Pixelfusion\Support\Facades\Config::class,", str_repeat(' ', 8)) . PHP_EOL;
        $facadeClass = $this->providerManager->getFacadeClass($this->package);

        $this->assertStringMatchesFormat($assert, $facadeClass);
    }

    public function testGetProviderClass()
    {
        $assert = sprintf("%sPixelfusion\PathExample\SampleServiceProvider::class,", str_repeat(' ', 8)) . PHP_EOL;
        $facadeClass = $this->providerManager->getProviderClass($this->package);

        $this->assertStringMatchesFormat($assert, $facadeClass);
    }
}
