<?php
namespace Skeletor\App\Config;

use Symfony\Component\Yaml\Yaml;

class SkeletorConfigurator
{
    protected $options;
    protected $optionFile;

    /**
     * SkeletorConfigurator constructor.
     */
    public function __construct()
    {
        $this->optionFile = __DIR__.'/skeletor.yml';
        $this->options = Yaml::parse(file_get_contents($this->optionFile));
    }

    /**
     * @return array
     */
    public function getManagers()
    {
        return $this->options['managers'];
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

    /**
     * @param array $options
     */
    public function storeConfig(array $options)
    {
        $yaml = Yaml::dump($options);
        file_put_contents($this->optionFile, $yaml);
    }
}