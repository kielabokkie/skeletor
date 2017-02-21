<?php
namespace Skeletor\Frameworks;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class Laravel54Framework extends Framework
{
    public function __construct(ComposerManager $composerManager, Filesystem $filesystem)
    {
        parent::__construct($composerManager, $filesystem);
        $this->setFramework('laravel');
        $this->setName("Laravel");
        $this->setVersion("5.4");
    }

    public function tidyUp()
    {
        $this->filesystem->delete('server.php');
        $this->filesystem->deleteDir('resources/assets');
        $this->filesystem->createDir('setup/git-hooks');
    }
}