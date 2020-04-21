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
    public function homeAction()
    {

        $em = $this->getDoctrine()->getManager();

        $comics = $em->getRepository('App:Comic')->findByHomepage();

        return  [ 'comics' => $comics];
    }
}
