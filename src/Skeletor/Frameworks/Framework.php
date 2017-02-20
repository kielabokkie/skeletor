<?php
namespace Skeletor\Frameworks;

use Skeletor\Manager\ComposerManager;

/**
 * Class Framework
 * @package Skeletor\Frameworks
 */
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

    /**
     * @return mixed
     */
    public function getFramework()
    {
        return $this->framework;
    }

    /**
     * @param mixed $framework
     */
    public function setFramework($framework)
    {
        $this->framework = $framework;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string $version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}