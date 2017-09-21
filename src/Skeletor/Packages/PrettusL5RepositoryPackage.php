<?php
namespace Skeletor\Packages;

use Skeletor\Packages\Interfaces\PublishablePackageInterface;

class PrettusL5RepositoryPackage extends Package implements PublishablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug("prettus/l5-repository");
        $this->setName("Prettus L5 Repository");
        $this->setProvider('Prettus\Repository\Providers\RepositoryServiceProvider');
    }
}
