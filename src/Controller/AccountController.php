<?php

namespace App\Controller;

use App\Entity\Persona;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
