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
        $this->setVersion("v3.3.0");
        $this->setOptions("--dev");
    }

    public function tidyUp(Filesystem $filesystem)
    {
        //$filesystem->delete('server.php');
    }
}