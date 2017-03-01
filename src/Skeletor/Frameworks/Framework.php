<?php
namespace Skeletor\Frameworks;

use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Skeletor\Manager\ComposerManager;

abstract class Framework implements FrameworkInterface
{
    protected $projectFilesystem;
    protected $composerManager;
    protected $mountManager;
    protected $installSlug;
    protected $options;
    protected $version;
    protected $paths;
    protected $name;

    public function __construct(ComposerManager $composerManager, Filesystem $projectFilesystem, MountManager $mountManager, array $options)
    {
        $this->projectFilesystem = $projectFilesystem;
        $this->composerManager = $composerManager;
        $this->mountManager = $mountManager;
        $this->options = $options;
        $this->setup();
    }

    public function getInstallSlug()
    {
        return $this->installSlug;
    }

    public function setInstallSlug(string $installSlug)
    {
        $this->installSlug = $installSlug;
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
        $command = $this->composerManager->prepareFrameworkCommand($this->getInstallSlug(), $this->getVersion());
        $this->composerManager->runCommand($command);
    }
}