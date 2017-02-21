# Skeletor

Skeletor allows you to easily setup a base Laravel project with [our atomic design](https://github.com/pixelfusion/base-atomic-design) frontend magic applied to it.

If you already have Skeletor installed you can skip to [create a project skeleton](#create-a-project-skeleton) section.

## Install Skeletor

Run the following command to build the phar file:

```bash
./vendor/bin/box build
```

For ease of use you can copy the generated `skeletor.phar` file to your `bin` directory:

```bash
mv skeletor.phar /usr/local/bin/skeletor
```

## Create a project skeleton

Create a directory for your project and use skeletor to setup the project skeleton:

```bash
mkdir myproject && myproject && skeletor project:create
```

## DryRun option

When you want to test the install proces, you can run a dryrun.
```bash
mkdir myproject && myproject && skeletor project:create --dryrun
```