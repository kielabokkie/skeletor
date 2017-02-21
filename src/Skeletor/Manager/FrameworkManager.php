<?php
namespace Skeletor\Manager;

use Skeletor\Frameworks\Exception\FailedToLoadFrameworkException;
use League\Flysystem\Filesystem;
use Skeletor\Frameworks\Framework;

class FrameworkManager
{
    /**
     * @var array with frameworks
     */
    protected $frameworks;

    /**
     * @var instance of the filesystem
     */
    protected $fileSystem;

    public function __construct(Filesystem $fileSystem)
    {
        $this->filesystem = $fileSystem;
    }

    public function addFramework(Framework $framework)
    {
        $this->frameworks[] = $framework;
    }

    public function getFrameworkNames()
    {
        return array_map(function($framework) {
            return $framework->getName() . ' ' . $framework->getVersion();
        }, $this->frameworks);
    }

    public function load(string $name)
    {
        foreach($this->frameworks as $key => $framework) {
            if($framework->getName() . ' ' . $framework->getVersion() === $name) {
                return $framework;
            }
        }

        throw new FailedToLoadFrameworkException('Failed to find framework '.$name);
    }

    public function install(Framework $framework)
    {
        $framework->install();
    }

    public function tidyUp(Framework $framework)
    {
        $framework->tidyUp($this->filesystem);
    }
}