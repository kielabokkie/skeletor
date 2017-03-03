<?php
namespace Skeletor\App\Config;

use Symfony\Component\Yaml\Yaml;

class SkeletorConfigurator
{
    protected $options;

    /**
     * SkeletorConfigurator constructor.
     */
    public function __construct()
    {
        $this->options = Yaml::parse(file_get_contents(__DIR__.'/skeletor.yml'));
    }

    /**
     * @return array
     */
    public function getFrameworks()
    {
        return $this->options['frameworks'];
    }

    /**
     * @return array
     */
    public function getPackages()
    {
        return $this->options['packages'];
    }

    /**
     * @return array
     */
    public function getDefaultPackages()
    {
        return $this->options['defaultPackages'];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->options['config']['name'];
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->options['config']['version'];
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->options;
    }
}