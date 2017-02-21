<?php
namespace Skeletor\Frameworks;

use League\Flysystem\Filesystem;

interface FrameworkInterface
{
    public function getFramework();
    public function setFramework(string $framework);
    public function getName();
    public function setName(string $name);
    public function getVersion();
    public function setVersion(string $version);
    public function install();
    public function tidyUp(Filesystem $filesystem);
}