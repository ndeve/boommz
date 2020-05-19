<?php

namespace App\Controller;

use App\Entity\Box;
use App\Entity\Bubble;
use App\Entity\Comic;
use App\Entity\Page;
use App\Form\ComicType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class ContestController extends Controller
{

    /**
     * @Route(  path="/contest/{rewritten}-{id}",
     *          name="contest",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function contest(Comic $contest, string $rewritten, Request $request)
    {
        return ['comic' => $contest];
    }


    /**
     * @Route(  path="/contest/{rewritten_contest}-{id_contest}/{rewritten}-{id}",
     *          name="contest_comic",
     *          requirements={
     *                  "rewritten_contest"="[a-z0-9-]+", "id_contest"= "\d+",
     *                  "rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     *
     * @Entity("comicContest", expr="repository.find(id_contest)")
     * @Entity("comic", expr="repository.find(id)")
     *
     * @Template
     */
    public function comic(Comic $comicContest, Comic $comic, string $rewritten, string $rewritten_contest)
    {
        if($comicContest->getRewritten() != $rewritten_contest || $rewritten != $comic->getRewritten() ){
            return $this->redirect($this->generateUrl($comic->getRouteName(), $comic->getRouteParams() ));
        }

        if ($this->getUser()) {
            $rate = $this->getDoctrine()->getManager()->getRepository('App:Rate')
              ->findOneByCriteria(['user' => $this->getUser(), 'comic' => $comic]);
        }

        return [ 'comic' => $comic, 'comicContest' => $comicContest, 'userRate' => $rate ?? null ];
    }


    /**
     * @Route(  path="/contest/{rewritten}-{id}/participate",
     *          name="contest_participate",
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

        $page->addBox($box1);
        $comic->addPage($page);

        $comic->setLocale($request->getLocale())
            ->setPublic(1)
            ->setComicContest($comicContest);

        if ($user = $this->getUser()) {
            $comic->setAuthor($this->getUser());
        }

        $form = $this->createForm(ComicType::class, $comic);

        $form->add('publish', SubmitType::class)
            ->add('draft', SubmitType::class);

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
          'personas' => $entityManager->getRepository('App:Persona')->findByParams(['star' => 1]),
          'backgrounds' => $entityManager->getRepository('App:Background')->findBy(['selection' => true])
        ]);
    }

}
