# Skeletor

[![Build Status](https://img.shields.io/travis/pixelfusion/skeletor.svg?branch=master&style=flat-square)](https://travis-ci.org/pixelfusion/skeletor)
[![NZ](http://img.shields.io/badge/made%20in-nz-blue.svg?style=flat-square)](http://pixelfusion.co.nz)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Skeletor is a PHP based CLI tool that has been built to take away the pain of setting up a base project skeleton for Laravel & Lumen projects. When creating a project using Skeletor you will be able to pick and choose from a set of packages and it automates a lot of the manual configuration like package providers, facades and environment variables. It also enables you to automatically install certain packages that you need for every project.

If you already have Skeletor installed you can skip to the [create a project](#create-a-project) section.


## Install Skeletor

Download the Skeletor installer using Composer:

```bash
composer global require pixelfusion/skeletor
```

Make sure to place the `~/.composer/vendor/bin` directory (or the equivalent directory for your OS) in your $PATH so the `Skeletor` executable can be located by you system.

## Update Skeletor

If you already have installed Skeletor and you need to update you can use the following command:

```bash
composer global update pixelfusion/skeletor
```


## Create a project

Once installed, the `skeletor project:create` command will create a fresh project installation in the directory you specify. For instance, `skeletor project:create helloworld` will create a directory named `helloworld` containing a fresh project installation with all of the selected packages and templates.

```bash
skeletor project:create helloworld
```

[![asciicast](https://asciinema.org/a/pyTg9Qh36cDa3sJaS6T3WLvca.png)](https://asciinema.org/a/pyTg9Qh36cDa3sJaS6T3WLvca)

## Customisation

It's easy to change what packages Skeletor offers you to install when creating a new project. Packages are configured in the `App/Config/skeletor.yml` file, this is where you can add new packages or remove the ones you don't need.

### Adding packages
You can also add a package with `skeletor package:add package/name`, it will search on Packagist and give you some options.

### Removing packages
To remove a package just remove it from the `App/Config/skeletor.yml` file and remove the packages from the `Packages` directory.


## Dry-run option

When you want to test the install process, you can use the dry-run option.

```bash
skeletor project:create helloworld --dry-run
```
