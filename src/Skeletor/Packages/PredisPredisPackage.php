<?php
namespace Skeletor\Packages;

class PredisPredisPackage extends Package
{
    public function setup()
    {
        $this->setInstallSlug('predis/predis');
        $this->setName('Predis Predis');
    }
}
