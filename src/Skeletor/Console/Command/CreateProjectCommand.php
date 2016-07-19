<?php
namespace Skeletor\Console\Command;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CreateProjectCommand extends Command
{
    protected $filesystem;

    public function __construct()
    {
        parent::__construct();

        $adapter = new Local(getcwd());
        $this->filesystem = new Filesystem($adapter);
    }

    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new project skeleton');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion('Is this a Laravel project? [<comment>Y/n</comment>]: ', true);
        $isLaravel = $helper->ask($input, $output, $question);

        if ($isLaravel === true) {
            $output->writeln('<comment>> Creating Laravel project</comment>');
            $this->installLaravel();
            $this->tidyLaravel($output);
        }

        $question = new ConfirmationQuestion('Is this a Lumen project? [<comment>Y/n</comment>]: ', true);
        $isLumen = $helper->ask($input, $output, $question);

        if ($isLumen === true) {
            $output->writeln('<comment>> Creating Lumen project</comment>');
            $this->installLumen();
            $this->tidyLumen($output);
        }

        $question = new ConfirmationQuestion('Atomic design? [<comment>Y/n</comment>]: ', true);
        $isAtomicDesign = $helper->ask($input, $output, $question);

        if ($isAtomicDesign === true) {
            $output->writeln('<comment>> Copying front end files</comment>');
            $this->installAtomicDesign();
            $this->tidyAtomicDesign();
        }
    }

    private function installLaravel()
    {
        $process = new Process('composer create-project --prefer-dist laravel/laravel .');
        $process->setTimeout(500);
        $process->run();

        if ($process->isSuccessful() === false) {
            throw new ProcessFailedException($process);
        }
    }

    private function tidyLaravel()
    {
        $this->filesystem->delete('server.php');
        $this->filesystem->deleteDir('resources/assets');
        $this->filesystem->createDir('setup/git-hooks');
    }

    private function installLumen()
    {
        $process = new Process('composer create-project --prefer-dist laravel/lumen .');
        $process->setTimeout(500);
        $process->run();

        if ($process->isSuccessful() === false) {
            throw new ProcessFailedException($process);
        }
    }

    private function tidyLumen()
    {
        $this->filesystem->createDir('setup/git-hooks');
    }

    private function installAtomicDesign()
    {
        $process = new Process('git clone https://github.com/pixelfusion/base-atomic-design.git');
        $process->run();

        if ($process->isSuccessful() === false) {
            throw new ProcessFailedException($process);
        }
    }

    private function tidyAtomicDesign()
    {
        $this->filesystem->deleteDir('base-atomic-design/.git');

        // Delete some files that we don't need
        $this->filesystem->delete('base-atomic-design/.travis.yml');
        $this->filesystem->delete('base-atomic-design/LICENSE');
        $this->filesystem->delete('base-atomic-design/README.md');

        // Remove some files that will be replaced
        if ($this->filesystem->has('.gitignore') === true) {
            $this->filesystem->delete('.gitignore');
        }
        if ($this->filesystem->has('gulpfile') === true) {
            $this->filesystem->delete('gulpfile.js');
        }
        if ($this->filesystem->has('package.json') === true) {
            $this->filesystem->delete('package.json');
        }

        // We only want the index.php file from the Laravel project
        rename(getcwd() . '/public', getcwd() . '/public-temp');
        rename(getcwd() . '/base-atomic-design/public', getcwd() . '/public');
        $this->filesystem->copy('public-temp/index.php', 'public/index.php');
        $this->filesystem->deleteDir('public-temp');

        // Copy front end assets folder
        rename(getcwd() . '/base-atomic-design/resources/assets', getcwd() . '/resources/assets');
        $this->filesystem->deleteDir('base-atomic-design/resources');

        // Copy front end git hooks
        rename(getcwd() . '/base-atomic-design/setup/git-hooks', getcwd() . '/setup/git-hooks');
        $this->filesystem->deleteDir('base-atomic-design/setup');

        // Copy the remaining files to the root of the project
        $files = $this->filesystem->listContents('base-atomic-design');

        foreach ($files as $file) {
            $this->filesystem->copy(
                $file['path'],
                $file['basename']
            );
        }

        $this->filesystem->deleteDir('base-atomic-design');
    }
}
