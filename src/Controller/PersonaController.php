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
     * @Route(  path="characters",
     *          name="charactes"
     *      )
     * @Template
     */
    public function charactersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $personas = $em->getRepository('App:Persona')->findAllPublic();

        return [ 'personas' => $personas ];
    }

    /**
     * @Route(  path="characters/head",
     *          name="character_head"
     *      )
     * @Template
     */
    public function generateHeadAction(Request $request, Character $character)
    {

        $query = $request->query->get('query');

        $data = explode('-', $query);

        $newData = [];
        $newData['persona'] = '/persona/creator/women/0000.png';
        $newData['hair'] = $data[0];
        $newData['eyes'] = $data[1];
        $newData['nose'] = $data[2];
        $newData['hat'] = $data[3];
        $newData['trousers'] = $data[4];
        $newData['top'] = $data[5];
        $newData['vest'] = $data[6];
        $newData['mouth'] = $data[7];

        $url = $character->generateHeadCharacter($newData);
        die();
    }

    /**
     * @Route(  path="characters/create",
     *          name="character_create"
     *      )
     * @Template
     */
    public function createAction(Request $request, Character $character)
    {
        $form = $this->createForm(PersonaCreatorType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!$this->getUser()) {
                $url = $this->generateUrl('app_register');
                return $this->redirect($url);
            }

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

            $url = $this->generateUrl('character_create');
            return $this->redirect($url);
        }

        return [
          'form' => $form->createView(),
        ];
    }

}
