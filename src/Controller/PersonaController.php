<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\PersonaCreatorType;
use App\Service\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class PersonaController extends AbstractController
{

    /**
     * @Route(  path="boommzers/{rewritten}-{id}",
     *          name="boommzer",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"})
     * @Template
     */
    public function boommzer(Persona $persona)
    {

    	$em = $this->getDoctrine()->getManager();
    	$comics = $em->getRepository('App:Comic')->findFromPerso($persona);

        return [ 'persona' => $persona, 'comics' => $comics ];
    }

    /**
     * @Route(  path="characters",
     *          name="characters"
     *      )
     * @Template
     */
    public function characters()
    {
        $em = $this->getDoctrine()->getManager();
        $stars = $em->getRepository('App:Persona')->findByParams(['star' => 1]);
        $personas = $em->getRepository('App:Persona')->findByParams(['star' => 0]);

        return [ 'stars' => $stars, 'personas' => $personas ];
    }

    /**
     * @Route(  path="characters/head",
     *          name="character_head"
     *      )
     * @Template
     */
    public function generateHead(Request $request, Character $character)
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
     *      ),
     * @Route(  path="characters/edit/{id}",
     *          name="character_edit",
     *          requirements={"id"= "\d+"}
     *      )
     * @Template
     */
    public function create(Persona $persona = null, Request $request, Character $character)
    {
	    $em = $this->getDoctrine()->getManager();

	    $form = $this->createForm(PersonaCreatorType::class);

        if ($persona) {
            $aPersona = explode('-', $persona->getPath());

            if (count($aPersona) == 9) {
                $p = '/persona/creator/' . ((substr($aPersona[0], 0, 1) == 'm') ? 'men' : 'women') . '/' . substr($aPersona[0], 1) . '.png';

                $form->get('persona')->setData($p);
                $form->get('eyes')->setData($aPersona[1]);
                $form->get('nose')->setData($aPersona[2]);
                $form->get('top')->setData($aPersona[3]);
                $form->get('trousers')->setData($aPersona[4]);
                $form->get('vest')->setData($aPersona[5]);
                $form->get('mouth')->setData($aPersona[6]);
                $form->get('hair')->setData($aPersona[7]);
                $form->get('hat')->setData($aPersona[8]);

                $form->add('save', SubmitType::class, [ 'label'=>'Modifier', 'attr' => ['class' => 'button is-gold is-clearfix is-medium']]);
            }
        }

        $form->add('add', SubmitType::class, [ 'label'=>'Boommzer !', 'attr' => ['class' => 'button is-primary is-clearfix is-medium']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('add')->isClicked()) {
                $persona = new Persona();
            }

            if(!$this->getUser()) {
                $url = $this->generateUrl('app_register');
                return $this->redirect($url);
            }

            $url = $character->generateCharacter($form->getData());

            $persona->addUser($this->getUser())
                ->setPath($url)
                ->setCategory('B!')
                ->setPublic(1)
                ->setName('Name')
                ->setHasHead(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($persona);
            $entityManager->flush();

            $url = $this->generateUrl('character_create');
            return $this->redirect($url);
        }

        return [
          'form' => $form->createView(),
        'personas' => $em->getRepository('App:Persona')->findByParams([ 'star' => 1, 'hasHead' => true]),
        ];
    }

}
