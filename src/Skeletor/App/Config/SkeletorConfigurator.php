<?php

namespace Skeletor\App\Config;

use Skeletor\App\Exceptions\FailedFilesystem;
use Symfony\Component\Yaml\Yaml;

class SkeletorConfigurator
{
    public function getConfig($file)
    {
        if(!file_exists($file)){
            throw new FailedFilesystem(sprintf("Couldn't load config, for %s", $file));
        }

        return Yaml::parse(file_get_contents($file));
    }
}