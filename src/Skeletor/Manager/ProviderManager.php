<?php

namespace Skeletor\Manager;

use Skeletor\Frameworks\Framework;
use Skeletor\Packages\Package;

class ProviderManager extends Manager
{
    const PROVIDER_METHOD = 'getProviderClass';
    const FACADE_METHOD = 'getFacadeClass';

    /**
     * @param Package $package
     * @param Framework $activeFramework
     */
    public function configure(Package $package, Framework $activeFramework)
    {
        if ($activeFramework->configurable() === false) {
            $this->cli->red("Configure {$package->getInstallSlug()} manual for {$activeFramework->getName()}");
            return;
        }
        $configFile = $activeFramework->getPath('appConfig');
        $newContent = $this->getNewConfig(file($configFile), $package);
        $this->projectFilesystem->update($configFile, $newContent);
    }

    /**
     * @param array $configFile
     * @param Package $package
     * @return array|bool
     */
    public function getNewConfig(array $configFile, Package $package)
    {
        foreach($configFile as $key => $line) {
            $state = null;
            $cleanLine = trim(preg_replace('/[\t\s]+/', '', $line));

            switch ($cleanLine) {
                case "'providers'=>[":
                    $state = self::PROVIDER_METHOD;
                    break;
                case "'aliases'=>[":
                    $state = self::FACADE_METHOD;
                    break;
            }

            if ($cleanLine === "]," && $state !== null) {
                $previousLine = --$key;
                $configFile[$previousLine] .= $this->$state($package);
            }
        }

        return $configFile;
    }

    /**
     * @param Package $package
     * @return string|void
     */
    public function getFacadeClass(Package $package)
    {
        $facade = $package->getFacade();
        if (isset($facade) === false) {
            return;
        }

        $provider = explode('@', $package->getFacade());

        return sprintf("\t\t'%s' => %s::class,", $provider[0], $provider[1]) . PHP_EOL;
    }

    /**
     * @param Package $package
     * @return string|void
     */
    public function getProviderClass(Package $package)
    {
        $provider = $package->getProvider();
        if (isset($provider) === false) {
            return;
        }

        return sprintf("\t\t%s::class,", $provider) . PHP_EOL;
    }
}