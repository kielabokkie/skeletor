# Development
Because our frameworks, packages and templates constantly change. Skeletor needs to adopt those changes.

Adding new: framework, package or default package, is really easy. All of those dependencies are defined in `src/Skeletor/App/Config/skeletor.yml`. Once you add a new dependency, it will automatically be loaded in the service container.

## Frameworks
Add the name of the new Framework to the `skeletor.yml`. For example we add `Laravel55Framework`, now make a blueprint for the framework `src/Skeletor/Frameworks/Laravel55Framework.php`.
This blueprint will define, more detail about the framework and give you the freedom to configure it.

Take a look at the `FrameworkInterface.php` here are all available methods, also take a look at the [Flysystem docs](https://flysystem.thephpleague.com/api/).

## Packages

### With Skeletor
You can also add a package with `skeletor package:add package/name`, it will search on Packagist and give you some options.

### Doing it manualy
Add the name of the new package to the `skeletor.yml`. For example we add `TestPackage` and make a blueprint for the package `src/Skeletor/Packages/TestPackage.php`. This blueprint will define, more detail about the package and give you the freedom to configure it.

Once you've installed the basic package, you can configure it further. Take a look at the `PackageInterface.php` to see all available methods. A good example of a package with different configuration options is the `SentryPackage.php`.

### Default packages
Same process as above but you add the package under defaultPackages in the `skeletor.yml`.

### Templates
The templates are located under `src/Templates`, here you can add the desired templates (for a framework or package).
In the blueprint of the framework/package, you have access to those templates.

For example:
```php
    $this->mountManager->copy(
        'skeletor://'.$this->options['templatePath'].'/JsonBehatExtensionPackage/FeatureContext.php',
        'project://namespace/pixelfusion/bootstrap/FeatureContext.php'
    );
```

In the blueprint of the package, you also have access to the active framework paths:
```php
    $this->mountManager->copy(
        'skeletor://'.$this->options['templatePath'].'/JsonBehatExtensionPackage/FeatureContext.php',
        'project://'.$activeFramework->getPath('tests').'/functional/features/bootstrap/FeatureContext.php'
    );
```

## Testing
You can test the code with, the following command.
```
php vendor/bin/codecept run
```

## Deployment
After adding new frameworks/packages/templates, make a pull request.
When to pull request is accepted and released, you need to run `composer global update`.
