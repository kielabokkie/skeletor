<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

interface PackageInterface
{
    public function setup();
    public function getInstallSlug();
    public function setInstallSlug(string $installSlug);
    public function getName();
    public function setName(string $name);
    public function getVersion();
    public function setVersion(string $version);
    public function getPackageOptions();
    public function setPackageOptions(string $packageOptions);
    public function install();
}