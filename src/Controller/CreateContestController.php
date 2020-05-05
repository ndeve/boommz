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

class CreateContestController extends Controller
{

    /**
     * @Route(  path="/contest/{id}",
     *          name="comic_contest_create",
     *          requirements={"id"= "\d+"}
     *      )
     * @Template
     */
    public function create(Comic $comicContest, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $comic = new Comic();

        $page = new Page();

        $box1 = new Box();
        $page->addBox($box1);
        $comic->addPage($page);

        $comic->setLocale($request->getLocale())
            ->setPublic(1)
            ->setComicContest($comic);

        if ($user = $this->getUser()) {
            $comic->setAuthor($this->getUser());
        }

        $form = $this->createForm(ComicType::class, $comic);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($comic);
            $entityManager->flush();

            $url = $this->generateUrl('comic', $comic->getRouteParams());
            return $this->redirect($url);
        }

        return $this->render('create/create.html.twig', [
          'comic' => $comic,
          'comicContest' => $comicContest,
          'form' => $form->createView(),
          'personas' => $entityManager->getRepository('App:Persona')->findBy(['path' => 'stars/']),
          'backgrounds' => $entityManager->getRepository('App:Background')->findBy(['selection' => true])
        ]);
    }

}
