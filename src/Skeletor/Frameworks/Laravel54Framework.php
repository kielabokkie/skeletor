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
        $this->filesystem->put('PixelFusion.txt', '©PIXELFUSION');
        $this->filesystem->delete('server.php');
        $this->filesystem->deleteDir('resources/assets');
        $this->filesystem->createDir('setup/git-hooks');
    }
}