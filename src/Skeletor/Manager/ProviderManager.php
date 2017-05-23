<?php

namespace Skeletor\Manager;

use Skeletor\Frameworks\Framework;
use Skeletor\Packages\Package;

class ProviderManager extends Manager
{
    const PROVIDER_METHOD = 'getWriteableProvider';
    const FACADE_METHOD = 'getFacadeClass';

    /**
     * @param Package $package
     * @param Framework $activeFramework
     */
    public function configure(Package $package, Framework $activeFramework)
    {
        if ($activeFramework->getName() !== 'Laravel') {
            $this->cli->red("Configure {$package->getInstallSlug()} manual for {$activeFramework->getName()}, we currently only support Laravel");
            return;
        }
        $configFile = $activeFramework->getPath('appConfig');
        $newContent = $this->getNewConfig($configFile, $package);
        $this->projectFilesystem->update($configFile, $newContent);
    }

    /**
     * @param string $configFile
     * @param Package $package
     * @return array|bool
     */
    public function getNewConfig(string $configFile, Package $package)
    {
        $state = null;
        $appConfig = file($configFile);

        foreach($appConfig as $key => $line) {
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
                $appConfig[$previousLine] .= $this->$state($package);
                $state = null;
            }
        }

        return $appConfig;
    }

    /**
     * @param Package $package
     * @return string
     */
    public function getFacadeClass(Package $package)
    {
        [$alias, $facade] = explode('@', $package->getFacade());

        return sprintf("\t\t'%s' => %s::class, ", $alias, $facade) . PHP_EOL;
    }

    /**
     * @param Package $package
     * @return string
     */
    public function getProviderClass(Package $package)
    {

        return sprintf("\t\t%s::class,", $package->getProvider()) . PHP_EOL;
    }
}