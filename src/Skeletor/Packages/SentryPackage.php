<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;

class SentryPackage extends Package implements ConfigurablePackageInterface, PublishablePackageInterface
{
    public function setup()
    {
        $this->setInstallSlug('sentry/sentry-laravel');
        $this->setName('Sentry for Laravel');
        $this->setProvider('Sentry\SentryLaravel\SentryLaravelServiceProvider');
        $this->setFacade('Sentry@Sentry\SentryLaravel\SentryFacade');
        $this->setEnvironmentVariables([
            'SENTRY_DSN' => '',
        ]);
    }

    public function configure(Framework $activeFramework)
    {
        if ($activeFramework->getVersion() === '5.4.*') {
            // Remove the original file first or else copy won't work
            $this->mountManager->delete('project://app/Exceptions/Handler.php');

            $this->mountManager->copy(
                'skeletor://'.$this->options['templatePath'].'/SentryPackage/Exceptions/Handler.php',
                'project://app/Exceptions/Handler.php'
            );
        }
    }
}
