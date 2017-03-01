<?php
namespace Skeletor\App\Config;

use Symfony\Component\Yaml\Yaml;

class SkeletorConfigurator
{
    protected $options;

    public function __construct()
    {
        $this->options = Yaml::parse(file_get_contents(__DIR__.'/skeletor.yml'));
    }

    public function getFrameworks()
    {
        return $this->options['frameworks'];
    }

    public function getPackages()
    {
        return $this->options['packages'];
    }

    public function getDefaultPackages()
    {
        return $this->options['defaultPackages'];
    }

    public function getName()
    {
        return $this->options['config']['name'];
    }

    public function getVersion()
    {
        return $this->options['config']['version'];
    }

    public function getConfig()
    {
        return $this->options;
    }
}