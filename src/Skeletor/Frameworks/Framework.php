<?php
namespace Skeletor\Frameworks;

use League\Flysystem\Filesystem;
use Skeletor\Manager\ComposerManager;

abstract class Framework implements FrameworkInterface
{
    protected $projectFilesystem;
    protected $composerManager;
    protected $framework;
    protected $name;
    protected $version;
    protected $paths;
    protected $options;

    public function __construct(ComposerManager $composerManager, Filesystem $projectFilesystem, array $options)
    {
        $this->composerManager = $composerManager;
        $this->projectFilesystem = $projectFilesystem;
        $this->options = $options;
        $this->setup();
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

    public function getPath(string $path)
    {
        if(array_key_exists($path, $this->paths)){
            return $this->paths[$path];
        }

        return '';
    }

    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    public function install()
    {
        $command = $this->composerManager->prepareFrameworkCommand($this->getFramework(), $this->getVersion());
        $this->composerManager->runCommand($command);
    }
}