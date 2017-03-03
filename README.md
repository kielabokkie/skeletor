# Skeletor

Skeletor allows you to easily setup a base project, with our most used packages and templates.

If you already have Skeletor installed you can skip to [create a project skeleton](#create-a-project-skeleton) section.


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

When you want to test the install process, you can run a dry-run.
```bash
skeletor project:create helloworld --dry-run
```