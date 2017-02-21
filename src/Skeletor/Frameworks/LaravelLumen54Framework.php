<?php
namespace Skeletor\Frameworks;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class LaravelLumen54Framework extends Framework
{
    public function __construct(ComposerManager $composerManager, Filesystem $filesystem)
    {
        parent::__construct($composerManager, $filesystem);
        $this->setFramework('laravel');
        $this->setName("Lumen");
        $this->setVersion("5.4");
    }

    public function tidyUp()
    {
        $this->filesystem->createDir('setup/git-hooks');
    }
}