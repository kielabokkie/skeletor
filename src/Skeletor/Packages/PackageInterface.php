<?php
namespace Skeletor\Packages;

use League\Flysystem\Filesystem;

interface PackageInterface
{
    public function getPackage();
    public function setPackage(string $package);
    public function getName();
    public function setName(string $name);
    public function getVersion();
    public function setVersion(string $version);
    public function getOptions();
    public function setOptions(string $options);
    public function install();
    public function tidyUp(Filesystem $filesystem);
}