<?php
namespace Skeletor\Frameworks;

class LaravelLumen54Framework extends Framework
{
    public function __construct($composerManager)
    {
        parent::__construct($composerManager);
        $this->setFramework('laravel');
        $this->setName("Lumen");
        $this->setVersion("5.4");
    }

    public function install()
    {
        $command = $this->composerManager->prepareFrameworkCommand($this->getFramework(), strtolower($this->getName()), $this->getVersion());
        $this->composerManager->runCommand($command);
    }

    public function tidyUp($filesystem)
    {
        //$filesystem->delete('server.php');
    }
}