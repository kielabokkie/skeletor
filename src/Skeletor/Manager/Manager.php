<?php
namespace Skeletor\Manager;

use League\CLImate\CLImate;
use League\Flysystem\Filesystem;

abstract class Manager
{
    protected $cli;
    protected $filesystem;
    public $options;

    public function __construct(CLImate $cli, Filesystem $filesystem, array $options)
    {
        $this->cli = $cli;
        $this->options = $options;
        $this->filesystem = $filesystem;
    }
}