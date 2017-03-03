<?php
namespace Skeletor\Manager;

use League\CLImate\CLImate;
use League\Flysystem\Filesystem;

abstract class Manager
{
    protected $skeletorFilesystem;
    protected $options;
    protected $cli;

    /**
     * Manager constructor.
     * @param CLImate $cli
     * @param array $options
     */
  public function __construct(CLImate $cli, Filesystem $skeletorFilesystem, array $options)
    {
        $this->cli = $cli;
        $this->options = $options;
        $this->skeletorFilesystem = $skeletorFilesystem;
    }
}