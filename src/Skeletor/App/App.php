<?php
namespace Skeletor\App;

use League\Container\Container;
use Symfony\Component\Console\Application;

class App extends Application
{
    public $container;
    public $options;

    public function __construct(Container $container, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
        $this->container = $container;
        $this->options['basePath'] = realpath(__DIR__.'/../');
        $this->options['templatePath'] = '/Templates';
    }

    public function registerServices(bool $dryRun = false)
    {
        $this->options['dryRun'] = $dryRun;

        //Register multiple filesystems
        $this->container
            ->add('skeletorAdapter', 'League\Flysystem\Adapter\Local')
            ->withArgument($this->options['basePath']);
         $this->container
             ->add('projectAdapter', 'League\Flysystem\Adapter\Local')
             ->withArgument(getcwd());

         $this->container
             ->add('skeletorFilesystem', 'League\Flysystem\Filesystem')
             ->withArgument('skeletorAdapter');
        $this->container
            ->add('projectFilesystem', 'League\Flysystem\Filesystem')
            ->withArgument('projectAdapter');

        $managers = [
            'skeletor' => $this->container->get('skeletorFilesystem'),
            'project' => $this->container->get('projectFilesystem')
        ];
        $this->container
            ->add('MountManager', 'League\Flysystem\MountManager')
            ->withArgument($managers);

        $this->container
            ->add('Cli', 'League\CLImate\CLImate');

        $this->container
            ->add('ComposerManager', 'Skeletor\Manager\ComposerManager')
            ->withArgument('Cli')
            ->withArgument($this->options);

        $this->container
            ->add('PackageManager', 'Skeletor\Manager\PackageManager')
            ->withArgument('Cli')
            ->withArgument($this->options);

        $this->container
            ->add('FrameworkManager', 'Skeletor\Manager\FrameworkManager')
            ->withArgument('Cli')
            ->withArgument($this->options);

        $this->container
            ->add('Laravel54Framework', 'Skeletor\Frameworks\Laravel54Framework')
            ->withArgument('ComposerManager')
            ->withArgument('projectFilesystem')
            ->withArgument($this->options);
        $this->container
            ->add('LaravelLumen54Framework', 'Skeletor\Frameworks\LaravelLumen54Framework')
            ->withArgument('ComposerManager')
            ->withArgument('projectFilesystem')
            ->withArgument($this->options);

        $this->container
            ->add('BehatPackage', 'Skeletor\Packages\BehatPackage')
            ->withArgument('ComposerManager')
            ->withArgument('projectFilesystem')
            ->withArgument('MountManager')
            ->withArgument($this->options);
        $this->container
            ->add('JsonBehatExtensionPackage', 'Skeletor\Packages\JsonBehatExtensionPackage')
            ->withArgument('ComposerManager')
            ->withArgument('projectFilesystem')
            ->withArgument('MountManager')
            ->withArgument($this->options);

        $this->container
            ->add('GitHooksPackage', 'Skeletor\Packages\GitHooksPackage')
            ->withArgument('ComposerManager')
            ->withArgument('projectFilesystem')
            ->withArgument('MountManager')
            ->withArgument($this->options);
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
            $this->container->get('JsonBehatExtensionPackage')
        ];
    }

    public function getDefaultPackages()
    {
        return [
            $this->container->get('GitHooksPackage')
        ];
    }

    public function getFrameworkManager()
    {
        return $this->container->get('FrameworkManager');
    }

    public function getPackageManager()
    {
        return $this->container->get('PackageManager');
    }

    public function getCli()
    {
        return $this->container->get('Cli');
    }
}