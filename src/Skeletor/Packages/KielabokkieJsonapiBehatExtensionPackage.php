<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class KielabokkieJsonapiBehatExtensionPackage extends Package implements ConfigurablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug('kielabokkie/jsonapi-behat-extension');
        $this->setName('Kielabokkie Jsonapi Behat Extension');
    }

    public function configure(Framework $activeFramework)
    {
        $this->mountManager->copy(
            'skeletor://'.$this->options['templatePath'].'/JsonBehatExtensionPackage/FeatureContext.php',
            'project://'.$activeFramework->getPath('tests').'/Behat/Feature/bootstrap/FeatureContext.php'
        );
        $this->mountManager->copy(
            'skeletor://'.$this->options['templatePath'].'/JsonBehatExtensionPackage/example.feature',
            'project://'.$activeFramework->getPath('tests').'/Behat/Feature/example.feature'
        );
        $this->mountManager->copy(
            'skeletor://'.$this->options['templatePath'].'/JsonBehatExtensionPackage/behat.yml',
            'project://behat.yml'
        );
    }
}
