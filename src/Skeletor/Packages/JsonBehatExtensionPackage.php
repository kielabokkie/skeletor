<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class JsonBehatExtensionPackage extends Package
{
    public function setup()
    {
        $this->setInstallSlug('kielabokkie/jsonapi-behat-extension');
        $this->setName("Behat extension for testing JSON APIs");
    }

    public function configure(Framework $activeFramework)
    {
        $this->projectFilesystem->put('PixelFusion.txt', '©PIXELFUSION');
        $this->mountManager->copy(
            'skeletor://'.$this->options['templatePath'].'/JsonBehatExtensionPackage/FeatureContext.php',
            'project://'.$activeFramework->getPath('tests').'/functional/features/bootstrap/FeatureContext.php'
        );
    }
}