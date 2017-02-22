<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

class JsonBehatExtsionPackage extends Package
{
    public function __construct(ComposerManager $composerManager)
    {
        parent::__construct($composerManager);
        $this->setPackage('kielabokkie/jsonapi-behat-extension');
        $this->setName("Behat extension for testing JSON APIs");
    }

    public function tidyUp(Filesystem $filesystem)
    {
        //$filesystem->copy('server.php');
        //$filesystem->write('path/to/file.txt' );
    }
}