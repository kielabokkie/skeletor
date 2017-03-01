<?php
//Example framework test
namespace Skeletor\Frameworks;

class Test54Framework extends Framework
{
    public function setup()
    {
        $this->setInstallSlug('test/test');
        $this->setName("Test");
        $this->setVersion("5.4");
        $this->setPaths([
            'tests' => 'tests',
        ]);
    }

    public function configure()
    {
        //The project filesystem, gives you access to the relative path of the new project
        //For more info read: https://flysystem.thephpleague.com/api/
        $this->projectFilesystem->put('PixelFusion.txt', '©PIXELFUSION');
        $this->projectFilesystem->delete('server.php');
        $this->projectFilesystem->deleteDir('resources/assets');
        $this->projectFilesystem->createDir('setup/git-hooks');

        //With the mountManager you can talk between the Skeletor filesystem and project filesystem
        //For example copy templates
        $this->mountManager->copy(
            'skeletor://'.$this->options['templatePath'].'/JsonBehatExtensionPackage/FeatureContext.php',
            'project://namespace/pixelfusion/bootstrap/FeatureContext.php'
        );
    }
}