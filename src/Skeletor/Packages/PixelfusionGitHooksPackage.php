<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class PixelfusionGitHooksPackage extends Package
{
    public function setup()
    {
        $this->setInstallSlug('pixelfusion/git-hooks');
        $this->setName("PixelFusion Git Hooks");
    }

    public function configure(Framework $activeFramework)
    {
        $this->projectFilesystem->put('PixelFusion.txt', '©PIXELFUSION');
    }
}