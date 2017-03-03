<?php
namespace Skeletor\Frameworks;

interface FrameworkInterface
{
    public function setup();
    public function getInstallSlug();
    public function setInstallSlug(string $installSlug);
    public function getName();
    public function setName(string $name);
    public function getVersion();
    public function setVersion(string $version);
    public function getPath(string $path);
    public function setPaths(array $paths);
    public function install();
    public function configure();
}