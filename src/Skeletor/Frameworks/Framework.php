<?php
namespace Skeletor\Frameworks;

use Skeletor\Manager\ComposerManager;

abstract class Framework implements FrameworkInterface
{
    protected $composerManager;
    protected $framework;
    protected $name;
    protected $version;

    public function __construct(ComposerManager $composerManager)
    {
        $this->composerManager = $composerManager;
    }

    public function getFramework()
    {
        return $this->framework;
    }

    public function setFramework(string $framework)
    {
        $this->framework = $framework;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    public function install()
    {
        $command = $this->composerManager->prepareFrameworkCommand($this->getFramework(), strtolower($this->getName()), $this->getVersion());
        $this->composerManager->runCommand($command);
    }
}