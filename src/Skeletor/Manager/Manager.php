<?php
namespace Skeletor\Manager;

use League\CLImate\CLImate;

abstract class Manager
{
    protected $options;
    protected $cli;

    /**
     * Manager constructor.
     * @param CLImate $cli
     * @param array $options
     */
    public function __construct(CLImate $cli, array $options)
    {
        $this->cli = $cli;
        $this->options = $options;
    }
}