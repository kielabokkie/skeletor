<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Skeletor\Manager\ComposerManager;

abstract class Package implements PackageInterface
{
    protected $options;
    protected $mountManager;
    protected $composerManager;
    protected $projectFilesystem;
    protected $packageOptions = "";
    protected $version = "";
    protected $installSlug;
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

    public function getPackageOptions()
    {
        return $this->packageOptions;
    }

    public function setPackageOptions(string $packageOptions)
    {
        $this->packageOptions = $packageOptions;
    }

    public function install()
    {
        $command = $this->composerManager->preparePackageCommand($this->getInstallSlug(), $this->getVersion(), $this->getPackageOptions());
        $this->composerManager->runCommand($command);
    }
}