<?php
namespace Skeletor\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class CreateProjectCommand extends Command
{
    protected function configure()
    {
        $this->setName('project:create')
            ->setDescription('Create a new project skeleton');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new ConfirmationQuestion('Is this a Laravel project? [Y/n]', true);
        $isLaravel = $helper->ask($input, $output, $question);

        if ($isLaravel === true) {
            $output->writeln('Creating Laravel project');
        }
    }
}
