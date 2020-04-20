<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;
use JMS\I18nRoutingBundle\Router\I18nRouter;

class Comic {

    private $router;
    private $em;

    public function __construct(I18nRouter $router, EntityManager $em)
    {
        $this->router = $router;
        $this->em = $em;
    }

    public function generateImage()
    {

        $params = [ 'image' => 0 ];
        $comics = $this->em->getRepository('BoumzBoumzBundle:Comic')->findComics( $params );

        foreach($comics as $comic) {

             //$this->router->setDefaultLocale($comic->getLang());
            $url = $this->router->generate($comic->getRouteName('print'), $comic->getRouteParams(), true );
            echo $comic->getId() ."\n";
            $dir = 'web/images/c/'. substr($comic->getId(), -1) .'/'. substr($comic->getId(), -2, 1) .'/';

            $command = 'mkdir -m 777 -p '. $dir;
            $return = shell_exec($command);

            $image = $dir . $comic->getId() .'.jpg';
            $thumb = $dir . $comic->getId() .'_th.jpg';
            $share = $dir . $comic->getId() .'_share.jpg';

            $command = 'xvfb-run -a --server-args="-screen 0, 1024x768x24" wkhtmltoimage "'. $url .'" "'. $image .'"';
            $return_1 = shell_exec($command);

            $return = $command ."\n". $return_1;

            if($return_1) {
                $command = 'convert -gravity center -crop 900x+0+0 '. $image.' '. $image;
                shell_exec($command);
            }

            $command = 'xvfb-run -a --server-args="-screen 0, 1024x768x24" wkhtmltoimage "'. $url .'/share" "'. $share .'"';
            $return_2 = shell_exec($command);

            $return = $command ."\n". $return_2;

            if($return_2) {
                $command = 'convert -gravity center -crop 900x+0+0 '. $share.' '. $share;
                shell_exec($command);
            }

            $command = 'xvfb-run -a --server-args="-screen 0, 1024x768x24" wkhtmltoimage "'. $url .'/th" "'. $thumb .'"';
            $return_3 = shell_exec($command);

            $return .= "\n". $command ."\n". $return_3;

            if($return_3) {
                $command = 'convert -gravity North -crop 300x220+0+0 '. $thumb.' '. $thumb;
                shell_exec($command);
            }

            if($return_1 and $return_2 and $return_3) {
                $comic->setImage(true);
                $this->em->persist($comic);
                $this->em->flush();
            }

        }

        $return = 1;



        return $return;
    }
}