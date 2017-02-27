<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class GitHooksPackage extends Package
{
    public function setup()
    {
        $this->setPackage('pixelfusion/git-hooks');
        $this->setName("PixelFusion Git Hooks");
    }

    public function configure(Framework $activeFramework)
    {
        $this->filesystem->put('PixelFusion.txt', '©PIXELFUSION');
    }
}