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

class EditController extends Controller
{
    /**
     * @Route(  path="/comics/{rewritten}-{id}/edit",
     *          name="comic_edit",
     *          requirements={"id"= "\d+"}
     *      )
     * @Template
     */
    public function editAction(int $id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        if (null === $comic = $entityManager->getRepository('App:Comic')->findOneById($id)) {
            return $this->redirect($this->generateUrl('comic', $comic->getRouteParams() ));
        }

        if(!$comic->getAuthor() || ($comic->getAuthor() && $comic->getAuthor()->getId() != $this->getUser()->getId())) {
            return $this->redirect($this->generateUrl('comic', $comic->getRouteParams() ));
        }

        $originalPages = new ArrayCollection();
        $originalBoxes = new ArrayCollection();
        $originalBubbles = new ArrayCollection();

        foreach ($comic->getPages() as $page) {
            $originalPages->add($page);

            foreach ($page->getBoxes() as $box) {
                $originalBoxes->add($box);

                foreach ($box->getBubbles() as $bubble) {
                    $originalBubbles->add($bubble);
                }
            }
        }

        $form = $this->createForm(ComicType::class, $comic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $finalPages = new ArrayCollection();
            $finalBoxes = new ArrayCollection();
            $finalBubbles = new ArrayCollection();

            //Data sent
            foreach ($form->get('pages')->getData() as $page) {
                $finalPages->add($page);

                foreach ($page->getBoxes() as $box) {
                    $finalBoxes->add($box);

                    foreach ($box->getBubbles() as $bubble) {
                        $finalBubbles->add($bubble);
                    }

                }
            }

            //Data original
            foreach($originalBubbles as $bubble) {
                if (!$finalBubbles->contains($bubble)){
                    $entityManager->remove($bubble);
                }
            }
            foreach($originalBoxes as $box) {
                if (!$finalBoxes->contains($box)){
                    $entityManager->remove($box);
                }
            }

            $entityManager->persist($comic);
            $entityManager->flush();

            $url = $this->generateUrl('comic', $comic->getRouteParams());
            return $this->redirect($url);
        }

        return $this->render('create/create.html.twig', [
          'comic' => $comic,
          'form' => $form->createView(),
          'personas' => $entityManager->getRepository('App:Persona')->findBy(['path' => 'stars/']),
          'backgrounds' => $entityManager->getRepository('App:Background')->findBy(['selection' => true])
        ]);
    }

}
