<?php

namespace Skeletor\Frameworks;

interface FrameworkInterface
{
    public function getFramework();
    public function setFramework($framework);
    public function getName();
    public function setName($name);
    public function getVersion();
    public function setVersion($version);
    public function install();
    public function tidyUp($filesystem);
}