<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class JsonBehatExtensionPackage extends Package
{

    public function setup()
    {
        $this->setPackage('kielabokkie/jsonapi-behat-extension');
        $this->setName("Behat extension for testing JSON APIs");
    }

    public function configure(Framework $activeFramework)
    {
        $this->filesystem->put('PixelFusion.txt', '©PIXELFUSION');
        $this->filesystem->copy(
            $this->options['templatePath'].'/JsonBehatExtensionPackage/FeatureContext.php',
            $activeFramework->getPath('tests').'/functional/features/bootstrap/FeatureContext.php'
        );
    }
}