<?php
//Example package test
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class TestPackage extends Package
{
    public function setup()
    {
        $this->setInstallSlug('test/test');
        $this->setName("Test package");
        $this->setVersion("0.5");
        $this->setPackageOptions("--dev");
    }

    public function configure(Framework $activeFramework)
    {
        //The project filesystem, gives you access to the relative path of the new project
        //For more info read: https://flysystem.thephpleague.com/api/
        $this->projectFilesystem->put('PixelFusion.txt', '©PIXELFUSION');

        //With the mountManager you can talk between the Skeletor filesystem and project filesystem
        //You also have access to the active framework, so you can ask where the tests folder is located
        $this->mountManager->copy(
            'skeletor://'.$this->options['templatePath'].'/JsonBehatExtensionPackage/FeatureContext.php',
            'project://'.$activeFramework->getPath('tests').'/functional/features/bootstrap/FeatureContext.php'
        );
    }
}