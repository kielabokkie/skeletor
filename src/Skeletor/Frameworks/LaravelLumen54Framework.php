<?php
namespace Skeletor\Frameworks;

class LaravelLumen54Framework extends Framework
{
    public function setup()
    {
        $this->setInstallSlug('laravel/lumen');
        $this->setName("Lumen");
        $this->setVersion("5.4");
    }

    public function configure()
    {
        $this->projectFilesystem->put('PixelFusion.txt', '©PIXELFUSION');
        $this->projectFilesystem->createDir('setup/git-hooks');
    }
}