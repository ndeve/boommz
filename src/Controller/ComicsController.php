<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ComicsController extends Controller
{
    
    /**
     * @Route(  path="/comics/",
     *          name="comics"
     *      )
     * @Template
     */
    public function comicsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comics = $em->getRepository('App:Comic')->findByParams([]);

        return [ 'comics' => $comics ];
    }
}
