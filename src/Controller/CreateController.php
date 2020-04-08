<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ComicType;
use App\Entity\Comic;
use App\Entity\Page;

class CreateController extends Controller
{
    /**
     * @Route(  path="/create",
     *          name="comic_create"
     *      )
     * @Route(  path="/{id}-{rewritten}/edit",
     *          name="boumz_comic_edit",
     *          requirements={"id"= "\d+"}
     *      )
     * @Template
     */
    public function createAction($id = null, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if(!isset($comic)) {
            $comic = new Comic();
            $comic->setLocale($request->getLocale())
              ->setPublic(1);

            $page = new Page();
            $comic->getPages()->add($page);
        }

        $form = $this->createForm(ComicType::class, $comic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$entityManager->getBoxes()->clear();
            $entityManager->persist($comic);
            $entityManager->flush();


            $entityManager->flush();

            $url = $this->generateUrl('comic', $comic->getRouteParams() );
            return $this->redirect($url);
        }

        return [
          'comic'             => $comic,
          'form'        => $form->createView(),
          'personas'          => [],//$em->getRepository('BoumzBoumzBundle:Persona')->findPersonas([ 'user' => $user ]),
          'backgrounds'       => [],//$em->getRepository('BoumzBoumzBundle:Background')->findBackgrounds([ 'user' => $user ])
        ];
    }
}
