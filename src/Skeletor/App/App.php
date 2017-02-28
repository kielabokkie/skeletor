<?php
namespace Skeletor\App;

use League\Container\Container;
use Skeletor\App\Config\SkeletorConfigurator;
use Skeletor\App\Exceptions\FailedToLoadService;
use Symfony\Component\Console\Application;

class App extends Application
{
    public $configurator;
    public $container;
    public $options;

    public function __construct(SkeletorConfigurator $configurator, Container $container, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct($name, $version);
        $this->container = $container;
        $this->configurator = $configurator;
        $this->options['basePath'] = realpath(__DIR__.'/../');
        $this->options['templatePath'] = '/Templates';
    }

    public function registerServices(bool $dryRun = false)
    {
        $this->options['dryRun'] = $dryRun;

        $this->registerFilesystem();
        $this->registerTools();
        $this->registerManagers();
        $this->registerFrameworks();
        $this->registerPackages();
    }

    public function registerFilesystem()
    {
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
    }

    public function registerTools()
    {
        $this->container
            ->add('Cli', 'League\CLImate\CLImate');
    }

    public function registerManagers()
    {
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
    }

    public function registerFrameworks()
    {
        foreach($this->configurator->getFrameworks() as $key => $framework)
        {
            $frameworkClass = sprintf('Skeletor\Frameworks\%s', $framework);

            if(!class_exists($frameworkClass)) {
                throw new FailedToLoadService("Couldn't find class ". $frameworkClass);
            }

            $this->container
                ->add($framework, $frameworkClass)
                ->withArgument('ComposerManager')
                ->withArgument('projectFilesystem')
                ->withArgument($this->options);
        }
    }

    public function registerPackages()
    {
        $packages = array_merge($this->configurator->getPackages(), $this->configurator->getDefaultPackages());
        foreach($packages as $key => $package)
        {
            $packageClass = sprintf('Skeletor\Packages\%s', $package);

            if(!class_exists($packageClass)) {
                throw new FailedToLoadService("Couldn't find class ". $package);
            }

            $this->container
                ->add($package, $packageClass)
                ->withArgument('ComposerManager')
                ->withArgument('projectFilesystem')
                ->withArgument('MountManager')
                ->withArgument($this->options);
        }
    }

    public function getFrameworks()
    {
        return array_map(function($framework) {
            return $this->container->get($framework);
        }, $this->configurator->getFrameworks());
    }

    public function getPackages()
    {
        return array_map(function($package) {
            return $this->container->get($package);
        }, $this->configurator->getPackages());
    }

    public function getDefaultPackages()
    {
        return array_map(function($defaultPackage) {
            return $this->container->get($defaultPackage);
        }, $this->configurator->getDefaultPackages());
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