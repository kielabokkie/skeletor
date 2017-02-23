<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class JsonBehatExtsionPackage extends Package
{
    public function __construct(ComposerManager $composerManager, Filesystem $filesystem)
    {
        parent::__construct($composerManager, $filesystem);
        $this->setPackage('kielabokkie/jsonapi-behat-extension');
        $this->setName("Behat extension for testing JSON APIs");
    }

    public function tidyUp()
    {
        $this->filesystem->put('PixelFusion.txt', '©PIXELFUSION');
    }
}