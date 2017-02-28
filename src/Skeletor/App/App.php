<?php
namespace Skeletor\App;

use League\CLImate\CLImate;
use League\Container\Container;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use League\Flysystem\Adapter\Local;
use Skeletor\Manager\PackageManager;
use Skeletor\Manager\ComposerManager;
use Skeletor\Manager\FrameworkManager;
use Symfony\Component\Console\Application;
use Skeletor\App\Config\SkeletorConfigurator;
use Skeletor\Exceptions\FailedToLoadService;

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
            ->add('skeletorAdapter', Local::class)
            ->withArgument($this->options['basePath']);
        $this->container
            ->add('projectAdapter', Local::class)
            ->withArgument(getcwd());

        $this->container
            ->add('skeletorFilesystem', Filesystem::class)
            ->withArgument('skeletorAdapter');
        $this->container
            ->add('projectFilesystem', Filesystem::class)
            ->withArgument('projectAdapter');

        $managers = [
            'skeletor' => $this->container->get('skeletorFilesystem'),
            'project' => $this->container->get('projectFilesystem')
        ];
        $this->container
            ->add('MountManager', MountManager::class)
            ->withArgument($managers);
    }

    public function registerTools()
    {
        $this->container
            ->add('Cli', CLImate::class);
    }

    public function registerManagers()
    {
        $this->container
            ->add('ComposerManager', ComposerManager::class)
            ->withArgument('Cli')
            ->withArgument($this->options);

        $this->container
            ->add('PackageManager', PackageManager::class)
            ->withArgument('Cli')
            ->withArgument($this->options);

        $this->container
            ->add('FrameworkManager', FrameworkManager::class)
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
        //Merge the optional and default packages, because the service is the same
        $packages = array_merge($this->configurator->getPackages(), $this->configurator->getDefaultPackages());
        foreach($packages as $key => $package)
        {
            $packageClass = sprintf('Skeletor\Packages\%s', $package);

            if(!class_exists($packageClass)) {
                throw new FailedToLoadService("Couldn't find class ". $packageClass);
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