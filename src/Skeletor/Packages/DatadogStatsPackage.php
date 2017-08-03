<?php
namespace Skeletor\Packages;

class DatadogStatsPackage extends Package
{
    public function setup()
    {
        $this->setInstallSlug('datadog/php-datadogstatsd');
        $this->setName('DataDog StatsD Client');
    }
}
