<?php
namespace Skeletor\Packages;

use Skeletor\Packages\Interfaces\PublishablePackageInterface;

class SpatieLaravelPermissionPackage extends Package implements PublishablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug('spatie/laravel-permission');
        $this->setName('Spatie Laravel Permission');
        $this->setProvider('Spatie\Permission\PermissionServiceProvider');
    }
}
