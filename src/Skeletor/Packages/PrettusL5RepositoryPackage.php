<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class PrettusL5RepositoryPackage extends Package implements ConfigurablePackageInterface, PublishablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug("prettus/l5-repository");
        $this->setName("Prettus L5 Repository");
        $this->setProvider('Prettus\Repository\Providers\RepositoryServiceProvider');
    }

    public function configure(Framework $activeFramework)
    {
    }
}
