<?php
namespace Skeletor\Frameworks;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class LaravelLumen54Framework extends Framework
{
    public function __construct(ComposerManager $composerManager)
    {
        parent::__construct($composerManager);
        $this->setFramework('laravel/lumen');
        $this->setName("Lumen");
        $this->setVersion("5.4");
    }

    public function tidyUp(Filesystem $filesystem)
    {
        $filesystem->createDir('setup/git-hooks');
    }
}