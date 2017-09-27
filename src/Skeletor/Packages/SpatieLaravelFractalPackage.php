<?php
namespace Skeletor\Packages;

use Skeletor\Packages\Interfaces\PublishablePackageInterface;

class SpatieLaravelFractalPackage extends Package implements PublishablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug('spatie/laravel-fractal');
        $this->setName('Spatie Laravel Fractal');
        $this->setProvider('Spatie\Fractal\FractalServiceProvider');
        $this->setFacade('Fractal@Spatie\Fractal\FractalFacade');
    }
}
