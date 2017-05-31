<?php
namespace Skeletor\Packages;

interface PackageInterface
{
    public function setup();
    public function getInstallSlug();
    public function setInstallSlug(string $installSlug);
    public function getName();
    public function setName(string $name);
    public function getVersion(bool $allowEmpty = true);
    public function setVersion(string $version);
    public function getPackageOptions();
    public function setPackageOptions(string $packageOptions);
    public function getProvider();
    public function setProvider(string $provider);
    public function getFacade();
    public function setFacade(string $facade);
    public function install();
}
