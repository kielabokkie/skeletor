<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Skeletor\Manager\ComposerManager;

abstract class Package implements PackageInterface
{
    public $options;
    public $mountManager;
    public $composerManager;
    public $projectFilesystem;
    protected $packageOptions = "";
    protected $version = "";
    protected $package;
    protected $name;

    public function __construct(ComposerManager $composerManager, Filesystem $projectFilesystem, MountManager $mountManager, array $options)
    {
        $this->composerManager = $composerManager;
        $this->mountManager = $mountManager;
        $this->projectFilesystem = $projectFilesystem;
        $this->options = $options;
        $this->setup();
    }

    public function getPackage()
    {
        return $this->package;
    }

    public function setPackage(string $package)
    {
        $this->package = $package;
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
        $this->packageOptionsoptions = $packageOptions;
    }

    public function install()
    {
        $command = $this->composerManager->preparePackageCommand($this->getPackage(), $this->getVersion(), $this->getPackageOptions());
        $this->composerManager->runCommand($command);
    }
}