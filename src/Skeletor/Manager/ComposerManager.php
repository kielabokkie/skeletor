<?php
namespace Skeletor\Manager;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ComposerManager extends Manager implements InstallerInterface
{
    /**
     * @param string $framework
     * @param string $version
     * @return string
     */
    public function prepareFrameworkCommand(string $framework, string $version)
    {
        return sprintf('composer create-project --prefer-dist --ansi %s:%s .', $framework, $version);
    }

    /**
     * @param string $package
     * @param string $version
     * @param string $packageOptions
     * @return string
     */
    public function preparePackageCommand(string $package, string $version, string $packageOptions)
    {
        return sprintf('composer require %s %s %s --sort-packages', $package, $version, $packageOptions);
    }

    /**
     * Get the composer file of the project
     *
     * @return array
     */
    public function getComposerFile()
    {
        return json_decode($this->projectFilesystem->read('composer.json'), true);
    }

    /**
     * Update the composer file of the project
     *
     * @param array $updates
     */
    public function updateComposerFile(array $updates)
    {
        $original = $this->getComposerFile();

        $result = array_merge_recursive($original, $updates);

        $this->projectFilesystem->put('composer.json', json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

}
