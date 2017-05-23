<?php
namespace Skeletor\Manager;

use League\CLImate\CLImate;
use League\Flysystem\Filesystem;

abstract class Manager
{
    protected $skeletorFilesystem;
    protected $projectFilesystem;
    protected $options;
    protected $cli;

    /**
     * Manager constructor.
     * @param CLImate $cli
     * @param Filesystem $projectFilesystem
     * @param Filesystem $skeletorFilesystem
     * @param array $options
     */
    public function __construct(CLImate $cli, Filesystem $skeletorFilesystem, Filesystem $projectFilesystem, array $options)
    {
        $this->cli = $cli;
        $this->options = $options;
        $this->projectFilesystem = $projectFilesystem;
        $this->skeletorFilesystem = $skeletorFilesystem;
    }
}