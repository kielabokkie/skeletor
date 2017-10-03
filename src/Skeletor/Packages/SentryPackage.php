<?php
namespace Skeletor\Packages;

use Skeletor\Frameworks\Framework;
use Skeletor\Packages\Interfaces\ConfigurablePackageInterface;
use Skeletor\Packages\Interfaces\PublishablePackageInterface;

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
        $original = 'project://app/Exceptions/Handler.php';

        // Delete the original file first or else copy won't work
        $this->mountManager->delete($original);

        switch ($activeFramework->getVersion()) {
            case '5.5.*':
                $this->mountManager->copy(
                    'skeletor://'.$this->options['templatePath'].'/SentryPackage/Exceptions/HandlerL55.php',
                    $original
                );
                break;
            case '5.4.*':
                $this->mountManager->copy(
                    'skeletor://'.$this->options['templatePath'].'/SentryPackage/Exceptions/HandlerL54.php',
                    $original
                );
                break;
        }
    }
}
