<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\PersonaCreatorType;
use App\Service\Character;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use App\Entity\User;

class AuthorController extends Controller
{

    /**
     * @Route(  path="authors",
     *          name="authors"
     *      )
     * @Template
     */
    public function authors()
    {
        $em = $this->getDoctrine()->getManager();
        $authors = $em->getRepository('App:User')->findAll();

        return [ 'authors' => $authors ];
    }

    /**
     * @Route(  path="authors/{rewritten}-{id}",
     *          name="author",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function author(User $user)
    {


        return [ 'author' => $user ];
    }

}
