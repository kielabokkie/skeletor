<?php
namespace Skeletor\Frameworks;

class Laravel54Framework extends Framework
{
    public function setup()
    {
        $this->setFramework('laravel/laravel');
        $this->setName("Laravel");
        $this->setVersion("5.4");
        $this->setPaths([
            'tests' => 'tests',
        ]);
    }

    public function configure()
    {
        $this->projectFilesystem->put('PixelFusion.txt', '©PIXELFUSION');
        $this->projectFilesystem->delete('server.php');
        $this->projectFilesystem->deleteDir('resources/assets');
        $this->projectFilesystem->createDir('setup/git-hooks');
    }
}