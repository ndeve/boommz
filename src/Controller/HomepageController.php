<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class HomepageController extends Controller
{
    /**
     * @Route(  path="/",
     *          name="homepage"
     *      )
     * @Template
     */
    public function home()
    {

        $em = $this->getDoctrine()->getManager();

        $dayComics = $em->getRepository('App:Comic')->findByParams(['selected' => 1, 'orderBy' => 'dateSelected']);

        $lastComics = $em->getRepository('App:Comic')->findByParams(['limit' => 12]);

        return  [ 'dayComics' => $dayComics, 'lastComics' => $lastComics];
    }
}
