<?php
namespace Skeletor\Frameworks;

class Laravel54Framework extends Framework
{
    public function setup()
    {
        $this->setInstallSlug('laravel/laravel');
        $this->setName("Laravel");
        $this->setVersion("5.4.*");
        $this->setPaths([
            'tests' => 'tests',
            'appConfig' => 'config/app.php'
        ]);
    }

    public function configure()
    {
        $this->projectFilesystem->deleteDir('resources/assets');
        $this->projectFilesystem->createDir('setup/git-hooks');

        // $this->mountManager->copy(
        //     'skeletor://'.$this->options['templatePath'].'/JsonBehatExtensionPackage/FeatureContext.php',
        //     'project://app/namespace/pixelfusion/bootstrap/FeatureContext.php'
        // );
    }
}
