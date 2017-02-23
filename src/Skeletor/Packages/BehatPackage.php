<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class BehatPackage extends Package
{
    public function __construct(ComposerManager $composerManager, Filesystem $filesystem)
    {
        parent::__construct($composerManager, $filesystem);
        $this->setPackage('behat/behat');
        $this->setName("Behat");
        $this->setVersion("v3.3.0");
        $this->setOptions("--dev");
    }

    public function tidyUp()
    {
        $this->filesystem->put('PixelFusion.txt', '©PIXELFUSION');
    }
}