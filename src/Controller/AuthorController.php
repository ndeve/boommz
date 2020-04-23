<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\PersonaCreatorType;
use App\Service\Character;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class AuthorController extends Controller
{

    /**
     * @Route(  path="authors",
     *          name="authors"
     *      )
     * @Template
     */
    public function authorsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $authors = $em->getRepository('App:User')->findAll();

        return [ 'authors' => $authors ];
    }

}
