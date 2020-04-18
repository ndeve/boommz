<?php

namespace App\Controller;

use App\Entity\Box;
use App\Form\ComicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comic;

class PersonaController extends Controller
{

    /**
     * @Route(  path="persona/create",
     *          name="persona_create"
     *      )
     * @Template
     */
    public function createAction()
    {

        return [];
    }
}
