<?php
namespace Skeletor\Frameworks;

use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Skeletor\Exceptions\FailedFilesystem;
use Skeletor\Manager\ComposerManager;
use Skeletor\Manager\RunManager;

abstract class Framework implements FrameworkInterface
{
    protected $projectFilesystem;
    protected $composerManager;
    protected $mountManager;
    protected $runManager;
    protected $installSlug;
    protected $options;
    protected $version;
    protected $paths;
    protected $name;

    /**
     * Framework constructor.
     * @param ComposerManager $composerManager
     * @param Filesystem $projectFilesystem
     * @param MountManager $mountManager
     * @param array $options
     */
    public function __construct(ComposerManager $composerManager, Filesystem $projectFilesystem, MountManager $mountManager, RunManager $runManager, array $options)
    {
        $this->projectFilesystem = $projectFilesystem;
        $this->composerManager = $composerManager;
        $this->mountManager = $mountManager;
        $this->runManager = $runManager;
        $this->options = $options;
        $this->setup();
    }

    /**
     * @return string
     */
    public function getInstallSlug()
    {
        return $this->installSlug;
    }

    /**
     * @param string $installSlug
     */
    public function setInstallSlug(string $installSlug)
    {
        $this->installSlug = $installSlug;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version)
    {
        $this->version = $version;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getPath(string $path)
    {
        if (!array_key_exists($path, $this->paths)) {
            throw new FailedFilesystem("Couldn't find {$path}");
        }

        return $this->paths[$path];
    }

    /**
     * @param array $paths
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    public function configure()
    {
        $this->mountManager->delete('project://readme.md');

        $this->mountManager->copy(
            'skeletor://' . $this->options['templatePath'] . '/readme.md',
            'project://readme.md'
        );

        $this->mountManager->copy(
            'skeletor://' . $this->options['templatePath'] . '/skeletor.md',
            'project://skeletor.md'
        );
    }

    public function install()
    {
        $command = $this->composerManager->prepareFrameworkCommand($this->getInstallSlug(), $this->getVersion());
        $this->runManager->runCommand($command);
    }
}
