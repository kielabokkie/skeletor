<?php
namespace Skeletor\App\Manager;

use Codeception\Util\Stub;
use League\CLImate\CLImate;
use Skeletor\Manager\ComposerManager;

class ComposerManagerTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $composerManager;

    protected function _before()
    {
        $cli = Stub::make(
            CLImate::class,
            [
            ]
        );
        $options = [];

        $this->composerManager = new ComposerManager($cli, $options);
    }

    protected function _after()
    {
    }

    // tests
    public function testPrepareFrameworkCommand()
    {
        $framework = 'frameworkname';
        $version = '0.1';
        $this->assertInternalType('string', $this->composerManager->prepareFrameworkCommand($framework, $version));
    }

    public function testPreparePackageCommand()
    {
        $package = 'packagename';
        $version = '0.1';
        $options = '';
        $this->assertInternalType('string', $this->composerManager->preparePackageCommand($package, $version, $options));
    }
}