<?php
namespace Skeletor\App\Manager;

use Codeception\Util\Stub;
use League\CLImate\CLImate;
use League\Flysystem\File;
use League\Flysystem\Filesystem;
use Skeletor\Frameworks\Framework;
use Skeletor\Frameworks\Laravel54Framework;
use Skeletor\Manager\FrameworkManager;

class FrameworkManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $frameworkManager;

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
            ]
        );
        $framework = Stub::make(
            Laravel54Framework::class,
            [
                'getName' => 'Laravel',
                'getVersion' => '5.4'
            ]
        );

        $options = [];
        $this->frameworkManager = new FrameworkManager($cli, $skeletorFilesystem, $options);

        //Load one framework
        $this->frameworkManager->setFrameworks([$framework]);
    }

    protected function _after()
    {
    }

    // tests
    public function testGetFrameworkNames()
    {
        $this->assertInternalType('array', $this->frameworkManager->getFrameworkNames());
    }

    public function testLoadFramework()
    {
        $this->assertInstanceOf(Framework::class, $this->frameworkManager->load('Laravel 5.4'));
    }
}