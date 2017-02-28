<?php
namespace Skeletor\Manager;

use Skeletor\Frameworks\Framework;
use Skeletor\App\Exceptions\FailedToLoadFrameworkException;

class FrameworkManager extends Manager
{
    /**
     * @var array with frameworks
     */
    protected $frameworks;

    public function setFrameworks(array $frameworks)
    {
        $this->frameworks = $frameworks;
    }

    public function getFrameworkNames()
    {
        return array_map(function($framework) {
            return $framework->getName() . ' ' . $framework->getVersion();
        }, $this->frameworks);
    }

    public function load(string $name)
    {
        foreach($this->frameworks as $key => $framework) {
            if($framework->getName() . ' ' . $framework->getVersion() === $name) {
                return $framework;
            }
        }

        throw new FailedToLoadFrameworkException('Failed to find framework '.$name);
    }

    public function getFrameworkOption()
    {
        $frameworkQuestion = $this->cli->radio('Choose your framework:', $this->getFrameworkNames());
        return $this->load($frameworkQuestion->prompt());
    }

    public function install(Framework $framework)
    {
        $framework->install();
    }

    public function configure(Framework $framework)
    {
        if(!$this->options['dryRun']) {
            $framework->configure();
        }
    }
}