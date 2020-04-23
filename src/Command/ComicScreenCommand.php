<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ComicScreenCommand extends Command
{
    protected static $defaultName = 'app:comic-screen';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $command = 'google-chrome --headless --hide-scrollbars --remote-debugging-port=9222 --disable-gpu --no-sandbox & node boommz/assets/js/screen.js --url="https://www.google.com" --outputDir="boommz/public/screen/"';

        shell_exec($command);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}