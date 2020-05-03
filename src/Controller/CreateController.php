<?php

namespace App\Controller;

use App\Entity\Box;
use App\Entity\Bubble;
use App\Entity\Comic;
use App\Entity\Page;
use App\Form\ComicType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CreateController extends Controller
{

    /**
     * @Route(  path="/create",
     *          name="comic_create"
     *      )
     * @Template
     */
    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $comic = new Comic();
        $comic->setLocale($request->getLocale())
          ->setPublic(1);
        if ($user = $this->getUser()) {
            $comic->setAuthor($this->getUser());
        }

        $page = new Page();

        $bubble = new Bubble();
        $persona = $entityManager->getRepository('App:Persona')
          ->findOneBy(['id' => 2105]);
        $bubble->setPersona($persona);

        $box1 = new Box();
        $box1->addBubble($bubble);
        $page->addBox($box1);

        $bubble = new Bubble();
        $persona = $entityManager->getRepository('App:Persona')
          ->findOneBy(['id' => 2105]);
        $bubble->setPersona($persona);

        $box2 = new Box();
        $box2->addBubble($bubble);
        $box2->clone = true;
        $page->addBox($box2);

        $bubble = new Bubble();
        $persona = $entityManager->getRepository('App:Persona')
          ->findOneBy(['id' => 2105]);
        $bubble->setPersona($persona);

        $box3 = new Box();
        $box3->addBubble($bubble);
        $box3->clone = true;
        $page->addBox($box3);
        $comic->addPage($page);

        $form = $this->createForm(ComicType::class, $comic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($comic);
            $entityManager->flush();

            $url = $this->generateUrl('comic', $comic->getRouteParams());
            return $this->redirect($url);
        }
        return [
          'comic' => $comic,
          'form' => $form->createView(),
          'personas' => $entityManager->getRepository('App:Persona')->findAllStars(),
          'backgrounds' => $entityManager->getRepository('App:Background')->findBy(['selection' => true]),
        ];
    }

}
