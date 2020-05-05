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

class ContestController extends Controller
{

    /**
     * @Route(  path="/contest/{rewritten}-{id}",
     *          name="contest",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function create(Comic $comicContest, string $rewritten, Request $request)
    {
        if ($comicContest->getRewritten() != $rewritten) {
            return $this->redirect($this->generateUrl('contest', $comicContest->getRouteParams() ));
        }

        $entityManager = $this->getDoctrine()->getManager();

        $comic = new Comic();

        $page = new Page();

        $nbBoxes = count($comicContest->getPages()[0]->getBoxes())-1;
        $box1 = clone($comicContest->getPages()[0]->getBoxes()[$nbBoxes]);

        foreach ($box1->getBubbles() as $bubble) {
            dump($bubble);
        }
        $page->addBox($box1);
        $comic->addPage($page);

        $comic->setLocale($request->getLocale())
            ->setPublic(1)
            ->setComicContest($comicContest);

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
