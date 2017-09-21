<?php
namespace Skeletor\Packages;

use Skeletor\Packages\Interfaces\PreInstallPackageInterface;

class PixelfusionGitHooksPackage extends Package implements PreInstallPackageInterface
{
    public function setup()
    {
        $this->setInstallSlug('pixelfusion/git-hooks');
        $this->setName('PixelFusion Git Hooks');
        $this->setPackageOptions('--dev');
    }

    public function preInstall()
    {
        $this->composerManager->runCommand('git init');

        $composerUpdates = [
            'scripts' => [
                'post-install-cmd' => [
                    './vendor/pixelfusion/git-hooks/src/git-hooks.sh',
                ],
                'post-update-cmd' => [
                    './vendor/pixelfusion/git-hooks/src/git-hooks.sh',
                ],
            ],
        ];

        $this->setComposerFileUpdates($composerUpdates);
    }
}
