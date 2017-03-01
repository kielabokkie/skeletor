# Development
Because our frameworks, packages and templates constantly change. Skeletor needs to adopt those changes.

Adding new: framework, package or default package, is really easy. All of those dependencies are defined in `src/Skeletor/App/Config/skeletor.yml`. Once you add a new dependency, it will automatically be loaded in the service container.

### Frameworks
Add the name of the new Framework to the `skeletor.yml`
For example we add `Test54Framework`, now make a blueprint for the framework `src/Skeletor/Frameworks/Test54Framework.php`.
This blueprint will define, more detail about the framework and give you the freedom to configure it.
See example: `Test54Framework.php`

### Packages
Add the name of the new package to the `skeletor.yml`
For example we add `TestPackage`, now make a blueprint for the package `src/Skeletor/Packages/TestPackage.php`.
This blueprint will define, more detail about the package and give you the freedom to configure it.
See example: `TestPackage.php`

### Default packages
Same process as the packages, only add this package name under defaultPackages in the `skeletor.yml`.

### Templates
Our templates are located under `src/Templates`, here you can add the desired templates (for a framework or package).

In the blueprint of the framework/package, you have access to those templates. See the `Test54Framework.php` and `TestPackage.php` for an example.

## Testing
You can test the code with, the following command.
```
php vendor/bin/codecept run
```

## Deployment
After adding new frameworks/packages/templates, make a pull request.
When to pull request is accepted and released, you need to run `composer global update`.