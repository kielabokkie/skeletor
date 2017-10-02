<?php
namespace Skeletor\Frameworks;

class LaravelLumen55Framework extends Framework
{
    public function setup()
    {
        $this->setInstallSlug('laravel/lumen');
        $this->setName("Lumen");
        $this->setVersion("5.5.*");
        $this->setPaths([
            'tests' => 'tests',
            'appConfig' => 'config/app.php'
        ]);
    }

    public function configure()
    {
        parent::configure();
    }

    public function configurable()
    {
        return false;
    }
}
