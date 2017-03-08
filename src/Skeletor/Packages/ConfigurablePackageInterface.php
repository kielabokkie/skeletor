<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

interface ConfigurablePackageInterface
{
    public function configure(Framework $framework);
}