<?php
namespace Skeletor\Frameworks;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

abstract class Framework implements FrameworkInterface
{
    protected $composerManager;
    protected $filesystem;
    protected $framework;
    protected $name;
    protected $version;

    public function __construct(ComposerManager $composerManager, Filesystem $filesystem)
    {
        $this->composerManager = $composerManager;
        $this->filesystem = $filesystem;
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
        $command = $this->composerManager->prepareFrameworkCommand($this->getFramework(), $this->getVersion());
        $this->composerManager->runCommand($command);
    }
}