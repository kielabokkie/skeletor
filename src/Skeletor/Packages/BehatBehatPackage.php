<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class BehatBehatPackage extends Package
{
    public function setup()
    {
        $this->setInstallSlug("behat/behat");
        $this->setName("Behat Behat");
    }

    public function configure(Framework $activeFramework)
    {
        //$this->projectFilesystem->put('PixelFusion.txt', '©PIXELFUSION');
    }
}