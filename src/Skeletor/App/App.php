<?php

namespace Skeletor\App;

use League\CLImate\CLImate;
use League\Container\Container;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use Skeletor\Api\PackagistApi;
use Skeletor\App\Config\SkeletorConfigurator;
use Skeletor\Exceptions\FailedToLoadService;
use Symfony\Component\Console\Application;

class App extends Application
{
    public $configurator;
    public $container;
    public $options;

    /**
     * App constructor.
     * @param SkeletorConfigurator $configurator
     * @param Container $container
     */
    public function __construct(SkeletorConfigurator $configurator, Container $container)
    {
        parent::__construct($configurator->getName(), $configurator->getVersion());
        $this->configurator = $configurator;
        $this->container = $container;

        $this->options['templatePath'] = '/Templates';
        $this->options['basePath'] = realpath(__DIR__ . '/../');
    }

    /**
     * @param bool $dryRun
     */
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
            'project' => $this->container->get('projectFilesystem'),
        ];
        $this->container
            ->add('MountManager', MountManager::class)
            ->withArgument($managers);
    }

    public function registerTools()
    {
        $this->container
            ->add('Cli', CLImate::class);

        $this->container
            ->add('PackagistApi', PackagistApi::class);
    }

    public function registerManagers()
    {
        foreach ($this->configurator->getManagers() as $key => $manager) {
            $managerClass = $this->getClassByReference('Skeletor\\Manager\\', $manager);

            $this->container
                ->add($manager, $managerClass)
                ->withArgument('Cli')
                ->withArgument('skeletorFilesystem')
                ->withArgument('projectFilesystem')
                ->withArgument($this->options);
        }
    }

    public function registerFrameworks()
    {
        foreach ($this->configurator->getFrameworks() as $key => $framework) {
            $frameworkClass = $this->getClassByReference('Skeletor\\Frameworks\\', $framework);

            $this->container
                ->add($framework, $frameworkClass)
                ->withArgument('ComposerManager')
                ->withArgument('projectFilesystem')
                ->withArgument('MountManager')
                ->withArgument('RunManager')
                ->withArgument($this->options);
        }
    }

    public function registerPackages()
    {
        //Merge the optional and default packages, because the service is the same
        $packages = array_merge($this->configurator->getPackages(), $this->configurator->getDefaultPackages());
        foreach ($packages as $key => $package) {
            $packageClass = $this->getClassByReference('Skeletor\\Packages\\', $package);

            $this->container
                ->add($package, $packageClass)
                ->withArgument('ComposerManager')
                ->withArgument('projectFilesystem')
                ->withArgument('MountManager')
                ->withArgument('RunManager')
                ->withArgument($this->options);
        }
    }

    /**
     * @param string $namespace
     * @param string $name
     * @return string
     */
    public function getClassByReference(string $namespace, string $name)
    {
        $packageClass = $namespace . $name;

        if (!class_exists($packageClass)) {
            throw new FailedToLoadService("Couldn't find class " . $packageClass);
        }

        return $packageClass;
    }

    /**
     * @return array
     */
    public function getFrameworks()
    {
        return array_map(function ($framework) {
            return $this->container->get($framework);
        }, $this->configurator->getFrameworks());
    }

    /**
     * @return array
     */
    public function getPackages()
    {
        return array_map(function ($package) {
            return $this->container->get($package);
        }, $this->configurator->getPackages());
    }

    /**
     * @return array
     */
    public function getDefaultPackages()
    {
        return array_map(function ($defaultPackage) {
            return $this->container->get($defaultPackage);
        }, $this->configurator->getDefaultPackages());
    }

    /**
     * @return object
     */
    public function getFrameworkManager()
    {
        return $this->container->get('FrameworkManager');
    }

    /**
     * @return object
     */
    public function getPackageManager()
    {
        return $this->container->get('PackageManager');
    }

    /**
     * @return object
     */
    public function getProviderManager()
    {
        return $this->container->get('ProviderManager');
    }

    /**
     * @return object
     */
    public function getCli()
    {
        return $this->container->get('Cli');
    }

    /**
     * @return object
     */
    public function getPackagistApi()
    {
        return $this->container->get('PackagistApi');
    }

    /**
     * @return object
     */
    public function getSkeletorFilesystem()
    {
        return $this->container->get('skeletorFilesystem');
    }

    /**
     * @return SkeletorConfigurator
     */
    public function getConfigurator()
    {
        return $this->configurator;
    }
}
