<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class BarryvdhLaravelDebugbarPackage extends Package implements ConfigurablePackageInterface, PublishablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug('barryvdh/laravel-debugbar');
        $this->setName('Barryvdh Laravel Debugbar');
        $this->setPackageOptions('--dev');
        $this->setProvider('Barryvdh\Debugbar\ServiceProvider');
        $this->setFacade('Debugbar@Barryvdh\Debugbar\Facade');
    }

    public function configure(Framework $activeFramework)
    {
    }
}
