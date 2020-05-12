<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Form\PersonaCreatorType;
use App\Service\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/avatar/{id}",
     *     name="avatar",
     *     requirements={"id"= "\d+"})
     */
    public function avatar(Persona $persona)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getUser()->setAvatar($persona);

        $entityManager->persist($user);
        $entityManager->flush();

        $url = $this->generateUrl('homepage');
        return $this->redirect($url);
    }

    /**
     * @Route("/account/my-avatar",
     *     name="myavatar")
     * @Template
     */
    public function myavatar(Request $request, Character $character)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $myavatar = explode('-', $this->getUser()->getAvatar()->getPath());

        $persona = '/persona/creator/'. ((substr($myavatar[0], 0, 1) == 'm') ? 'men' : 'women') .'/'. substr($myavatar[0], 1) .'.png';

        $form = $this->createForm(PersonaCreatorType::class);

        if (count($myavatar) == 9) {
            $form->get('persona')->setData($persona);
            $form->get('eyes')->setData($myavatar[1]);
            $form->get('nose')->setData($myavatar[2]);
            $form->get('top')->setData($myavatar[3]);
            $form->get('trousers')->setData($myavatar[4]);
            $form->get('vest')->setData($myavatar[5]);
            $form->get('mouth')->setData($myavatar[6]);
            $form->get('hair')->setData($myavatar[7]);
            $form->get('hat')->setData($myavatar[8]);
        }

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
              ->setName($this->getUser()->getUsername());

            $entityManager->persist($persona);

            $user = $this->getUser()->setAvatar($persona);
            $entityManager->persist($user);

            $entityManager->flush();

            $url = $this->generateUrl('myavatar');
            return $this->redirect($url);
        }

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/account/my-comics",
     *     name="mycomics")
     * @Template
     */
    public function mycomics()
    {
        return [];
    }

    /**
     * @Route("/account/my-drafts",
     *     name="mydrafts")
     * @Template
     */
    public function mydrafts()
    {

        $em = $this->getDoctrine()->getManager();

        $comics = $em->getRepository('App:Comic')->findByParams(['draft' => true, 'author' => $this->getUser()->getId()]);

        return [ 'comics' => $comics ];
    }
}
