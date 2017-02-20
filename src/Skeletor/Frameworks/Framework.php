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

    public function setFramework($framework)
    {
        $this->framework = $framework;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setVersion($version)
    {
        $this->version = $version;
    }
}