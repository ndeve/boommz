<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\PersonaCreatorType;
use App\Service\Character;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class PersonaController extends Controller
{

    /**
     * @Route(  path="character/create",
     *          name="character_create"
     *      )
     * @Template
     */
    public function createAction(Request $request, Character $character)
    {
        $form = $this->createForm(PersonaCreatorType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $character->generateCharacter($form->getData());

            $persona = new Persona();
            $persona->addUser($this->getUser())
                ->setPath($url)
                ->setCategory('B!')
                ->setPublic(1)
                ->setName('Name');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($persona);
            $entityManager->flush();

            $url = $this->generateUrl('persona_create');
            return $this->redirect($url);
        }

        return [
          'form' => $form->createView(),
        ];
    }

}
