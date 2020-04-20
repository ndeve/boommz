<?php

namespace App\Controller;

use App\Form\PersonaCreatorType;
use App\Service\Character;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class PersonaController extends Controller
{

    /**
     * @Route(  path="persona/create",
     *          name="persona_create"
     *      )
     * @Template
     */
    public function createAction(Request $request, Character $character)
    {
        $form = $this->createForm(PersonaCreatorType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $character->generateCharacter($form->getData());


            die();
        }

        return [
          'form' => $form->createView(),
        ];
    }

}
