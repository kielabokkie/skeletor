<?php
namespace Skeletor\Frameworks;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class Laravel54Framework extends Framework
{
    public function __construct(ComposerManager $composerManager)
    {
        parent::__construct($composerManager);
        $this->setFramework('laravel');
        $this->setName("Laravel");
        $this->setVersion("5.4");
    }

    public function tidyUp(Filesystem $filesystem)
    {
        //$filesystem->delete('server.php');
    }
}