<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class BehatPackage extends Package
{
    public function setup()
    {
        $this->setInstallSlug('behat/behat');
        $this->setName("Behat");
        $this->setVersion("v3.3.0");
        $this->setPackageOptions("--dev");
    }

    public function configure(Framework $activeFramework)
    {
        $this->projectFilesystem->put('PixelFusion.txt', '©PIXELFUSION');
    }
}