<?php
namespace Skeletor\Packages;

class BehatBehatPackage extends Package
{
    public function setup()
    {
        $this->setInstallSlug('behat/behat');
        $this->setName('Behat Behat');
        $this->setPackageOptions('--dev');
    }
}
