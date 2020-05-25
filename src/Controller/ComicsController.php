<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ComicsController extends Controller
{
    
    /**
     * @Route(  path="/comics/{page}",
     *          name="comics",
     *          requirements={"page"= "\d*"}
     *      )
     * @Template
     */
    public function comics(int $page = 0)
    {
        $em = $this->getDoctrine()->getManager();
        $limit = 40;
        $comics = $em->getRepository('App:Comic')->findByParams(['publish' => true, 'offset'=>$page*$limit, 'limit'=> $limit]);

        return [ 'comics' => $comics ];
    }

    /**
     * @Route(  path="/comics/bds-du-jour/",
     *          name="day_comics"
     *      )
     * @Template
     */
    public function dayComics()
    {
        $em = $this->getDoctrine()->getManager();

        $comics = $em->getRepository('App:Comic')->findByParams(['selected' => 1, 'orderBy' => 'dateSelected']);

        return [ 'comics' => $comics ];
    }
}
