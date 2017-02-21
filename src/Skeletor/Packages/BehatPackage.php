<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class BehatPackage extends Package
{
    public function __construct(ComposerManager $composerManager)
    {
        parent::__construct($composerManager);
        $this->setPackage('behat/behat');
        $this->setName("Behat");
        $this->setVersion("");
    }

    public function install()
    {
        $command = $this->composerManager->preparePackageCommand($this->getPackage(), $this->getVersion());
        $this->composerManager->runCommand($command);
    }

    public function tidyUp(Filesystem $filesystem)
    {
        //$filesystem->delete('server.php');
    }
}