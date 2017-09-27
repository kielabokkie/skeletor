<?php
namespace Skeletor\Packages\Interfaces;

use Skeletor\Frameworks\Framework;

interface ConfigurablePackageInterface
{
    public function configure(Framework $framework);
}
