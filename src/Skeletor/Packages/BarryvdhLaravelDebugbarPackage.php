<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class BarryvdhLaravelDebugbarPackage extends Package implements ConfigurablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug("barryvdh/laravel-debugbar");
        $this->setName("Barryvdh Laravel Debugbar");
    }

    public function configure(Framework $activeFramework)
    {

    }
}