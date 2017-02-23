<?php
namespace Skeletor\Manager;

use League\CLImate\CLImate;
use League\Flysystem\Filesystem;

class Manager
{
    public $cli;
    public $filesystem;
    public $dryRun;

    public function __construct(CLImate $cli, Filesystem $filesystem, bool $dryRun)
    {
        $this->cli = $cli;
        $this->dryRun = $dryRun;
        $this->filesystem = $filesystem;
    }
}