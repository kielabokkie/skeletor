<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class SpatieLaravelFractalPackage extends Package implements ConfigurablePackageInterface, PublishablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug('spatie/laravel-fractal');
        $this->setName('Spatie Laravel Fractal');
        $this->setProvider('Spatie\Fractal\FractalServiceProvider');
        $this->setFacade('Fractal@Spatie\Fractal\FractalFacade');
    }

    public function configure(Framework $activeFramework)
    {
    }
}
