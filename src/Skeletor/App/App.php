<?php
namespace Skeletor\App;

use League\Container\Container;
use Symfony\Component\Console\Application;

class App extends Application
{
    public $container;

    public function __construct(Container $container, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
        $this->container = $container;
    }

    public function registrateServices(bool $dryRun)
    {
        $this->container
            ->add('Cli', 'League\CLImate\CLImate');

        $this->container
            ->add('Filesystem', 'League\Flysystem\Filesystem')
            ->withArgument('Adapter');

        $this->container
            ->add('Adapter', 'League\Flysystem\Adapter\Local')
            ->withArgument(getcwd());

        $this->container
            ->add('ComposerManager', 'Skeletor\Manager\ComposerManager')
            ->withArgument('Cli')
            ->withArgument('Filesystem')
            ->withArgument($dryRun);

        $this->container
            ->add('PackageManager', 'Skeletor\Manager\PackageManager')
            ->withArgument('Cli')
            ->withArgument('Filesystem')
            ->withArgument($dryRun);

        $this->container
            ->add('FrameworkManager', 'Skeletor\Manager\FrameworkManager')
            ->withArgument('Cli')
            ->withArgument('Filesystem')
            ->withArgument($dryRun);

        $this->container
            ->add('Laravel54Framework', 'Skeletor\Frameworks\Laravel54Framework')
            ->withArgument('ComposerManager')
            ->withArgument('Filesystem');
        $this->container
            ->add('LaravelLumen54Framework', 'Skeletor\Frameworks\LaravelLumen54Framework')
            ->withArgument('ComposerManager')
            ->withArgument('Filesystem');

        $this->container
            ->add('BehatPackage', 'Skeletor\Packages\BehatPackage')
            ->withArgument('ComposerManager')
            ->withArgument('Filesystem');
        $this->container
            ->add('JsonBehatExtsionPackage', 'Skeletor\Packages\JsonBehatExtsionPackage')
            ->withArgument('ComposerManager')
            ->withArgument('Filesystem');

        $this->container
            ->add('GitHooksPackage', 'Skeletor\Packages\GitHooksPackage')
            ->withArgument('ComposerManager')
            ->withArgument('Filesystem');
    }

    public function getFrameworks()
    {
        return [
          $this->container->get('Laravel54Framework'),
          $this->container->get('LaravelLumen54Framework')
        ];
    }

    public function getPackages()
    {
        return [
            $this->container->get('BehatPackage'),
            $this->container->get('JsonBehatExtsionPackage')
        ];
    }

    public function getDefaultPackages()
    {
        return [
            $this->container->get('GitHooksPackage')
        ];
    }
}