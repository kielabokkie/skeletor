<?php
namespace Skeletor\Manager;

interface InstallerInterface
{
    public function prepareFrameworkCommand(string $framework, string $version);
    public function preparePackageCommand(string $package, string $version, string $packageOptions);
    public function runCommand(string $command);
}