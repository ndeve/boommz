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
     *          requirements={"page": "\d+"}
     *      )
     * @Template
     */
    public function comicsAction(int $page = 0)
    {

        $em = $this->getDoctrine()->getManager();

        $comics = $em->getRepository('App:Comic')->findByParams([]);// ['page' => $page] );

        return [ 'comics' => $comics ];
    }
}
