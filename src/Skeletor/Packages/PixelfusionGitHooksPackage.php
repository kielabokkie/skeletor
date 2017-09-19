<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class PixelfusionGitHooksPackage extends Package implements ConfigurablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug('pixelfusion/git-hooks');
        $this->setName('PixelFusion Git Hooks');
        $this->setPackageOptions('--dev');
    }

    public function configure(Framework $activeFramework)
    {
    }
}
