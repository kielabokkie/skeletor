<?php
namespace Skeletor\Packages;

use Skeletor\Manager\ComposerManager;

abstract class Package implements PackageInterface
{
    protected $composerManager;
    protected $package;
    protected $name;
    protected $version;

    public function __construct(ComposerManager $composerManager)
    {
        $this->composerManager = $composerManager;
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
}