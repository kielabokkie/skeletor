# Skeletor

[![Build Status](https://img.shields.io/travis/pixelfusion/skeletor.svg?branch=master&style=flat-square)](https://travis-ci.org/pixelfusion/skeletor)
[![NZ](http://img.shields.io/badge/made%20in-nz-blue.svg?style=flat-square)](http://pixelfusion.co.nz)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Skeletor allows you to easily setup a base project, with our most used packages and templates.

If you already have Skeletor installed you can skip to [create a project](#create-a-project) section.

### Install Skeletor

First, download the Skeletor installer using Composer:

```bash
composer global require "pixelfusion/skeletor"
```

Make sure to place the `$HOME/.composer/vendor/bin` directory (or the equivalent directory for your OS) in your $PATH so
the `Skeletor` executable can be located by you system.


### Create a project

Once installed, the `skeletor project:create` command will create a fresh project installation in the directory you specify.
For instance, `skeletor project:create helloworld` will create a directory named `helloworld`
containing a fresh project installation with all of the selected packages and templates.

```bash
skeletor project:create helloworld
```

#### Dry-run option

When you want to test the install process, you can use the dry-run option.

```bash
skeletor project:create helloworld --dry-run
```
