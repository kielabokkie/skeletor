<?php
namespace Skeletor\Manager;

use Skeletor\Frameworks\Framework;
use Skeletor\Exceptions\FailedToLoadFrameworkException;

class FrameworkManager extends Manager
{
    protected $frameworks;

    /**
     * @param array $frameworks
     */
    public function setFrameworks(array $frameworks)
    {
        $this->frameworks = $frameworks;
    }

    /**
     * @return array
     */
    public function getFrameworkNames()
    {
        return array_map(function($framework) {
            return $framework->getName() . ' ' . $framework->getVersion();
        }, $this->frameworks);
    }

    /**
     * @param string $name
     * @return object
     */
    public function load(string $name)
    {
        foreach($this->frameworks as $key => $framework) {
            if($framework->getName() . ' ' . $framework->getVersion() === $name) {
                return $framework;
            }
        }

        throw new FailedToLoadFrameworkException('Failed to find framework '.$name);
    }

    /**
     * @return object
     */
    public function getFrameworkOption()
    {
        $frameworkQuestion = $this->cli->radio('Choose your framework:', $this->getFrameworkNames());
        return $this->load($frameworkQuestion->prompt());
    }

    /**
     * @param Framework $framework
     */
    public function install(Framework $framework)
    {
        $framework->install();
    }

    /**
     * @param Framework $framework
     */
    public function configure(Framework $framework)
    {
        if(!$this->options['dryRun']) {
            $framework->configure();
        }
    }
}