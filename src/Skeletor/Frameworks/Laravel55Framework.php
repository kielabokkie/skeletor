<?php
namespace Skeletor\Frameworks;

class Laravel55Framework extends Framework
{
    public function setup()
    {
        $this->setInstallSlug('laravel/laravel');
        $this->setName("Laravel");
        $this->setVersion("5.5.*");
        $this->setPaths([
            'tests' => 'tests',
            'appConfig' => 'config/app.php'
        ]);
    }

    public function configure()
    {
        parent::configure();

        $this->projectFilesystem->deleteDir('public/css');
        $this->projectFilesystem->deleteDir('public/js');
        $this->projectFilesystem->delete('public/.htaccess');
        $this->projectFilesystem->delete('public/web.config');
    }

    public function configurable()
    {
        return true;
    }
}
