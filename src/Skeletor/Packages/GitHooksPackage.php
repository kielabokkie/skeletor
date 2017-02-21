<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class GitHooksPackage extends Package
{
    public function __construct(ComposerManager $composerManager)
    {
        parent::__construct($composerManager);
        $this->setPackage('pixelfusion/git-hooks');
        $this->setName("PixelFusion Git Hooks");
        $this->setVersion("");
    }

    public function tidyUp(Filesystem $filesystem)
    {
        //$filesystem->delete('server.php');
    }
}