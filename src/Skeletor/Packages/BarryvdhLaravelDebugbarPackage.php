<?php
namespace Skeletor\Packages;

use Skeletor\Packages\Interfaces\PublishablePackageInterface;

class BarryvdhLaravelDebugbarPackage extends Package implements PublishablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug('barryvdh/laravel-debugbar');
        $this->setName('Barryvdh Laravel Debugbar');
        $this->setPackageOptions('--dev');
        $this->setProvider('Barryvdh\Debugbar\ServiceProvider');
        $this->setFacade('Debugbar@Barryvdh\Debugbar\Facade');
    }
}
