<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class BarryvdhLaravelDebugbarPackage extends Package implements ConfigurablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug("barryvdh/laravel-debugbar");
        $this->setName("Barryvdh Laravel Debugbar");
        $this->setProvider('Barryvdh\Debugbar\ServiceProvider');
        $this->setFacade('Debugbar@Barryvdh\Debugbar\Facade');
    }

    public function configure(Framework $activeFramework)
    {

    }
}