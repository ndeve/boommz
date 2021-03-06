<?php
//google-chrome --headless --hide-scrollbars --remote-debugging-port=9222 --disable-gpu --no-sandbox &
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ComicScreenCommand extends Command
{
    protected static $defaultName = 'app:comic-screen';
    private $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }

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

        $em = $this->container->get('doctrine')->getManager();
        $comics = $em->getRepository('App:Comic')->findBy([ 'screen' => 0]);

        foreach ($comics as $comic) {
            $dir = '/home/wwwroot/boommz/public/'. $comic->getUrlScreen();

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $url = 'https://boommz.com'. $this->container->get('router')->generate('comic_screen', $comic->getRouteParams());
            echo $url ."\n";
            $height = (count($comic->getPages()[0]->getBoxes())*260) + 210;
            $command = 'node /home/wwwroot/boommz/assets/js/screen.js --url="'. $url .'" --outputDir="'. $dir
              .'" --output="'. $comic->getRewritten() .'-'. $comic->getId() .'.png" --viewportHeight='. $height .' --viewportWidth=450';
            echo $command;
            shell_exec($command);

            $url = 'https://boommz.com'. $this->container->get('router')->generate('comic_screen_fb', $comic->getRouteParams());
            echo $url ."\n";
            if (count($comic->getPages()[0]->getBoxes()) <= 4) {
                $height = 480;
            }
            else {
                $height = 750;
            }
            $command = 'node /home/wwwroot/boommz/assets/js/screen.js --url="'. $url .'" --outputDir="'. $dir
              .'" --output="fb-'. $comic->getRewritten() .'-'. $comic->getId() .'.png" --viewportHeight='. $height .' --viewportWidth=900';
            shell_exec($command);


            $comic->setScreen(true);
            $em->persist($comic);
            $em->flush();
        }


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
