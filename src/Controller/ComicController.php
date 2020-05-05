<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comic;
use App\Entity\Box;
use App\Entity\Rate;
use App\Form\ComicType;


class ComicController extends Controller
{

    /**
     * @Route(  path="/comics/{rewritten}-{id}/screen-fb",
     *          name="comic_screen_fb",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function comicScreenFb(Comic $comic, string $rewritten)
    {

        return [ 'comic' => $comic ];
    }

    /**
     * @Route(  path="/comics/{rewritten}-{id}/screen",
     *          name="comic_screen",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function comicScreen(Comic $comic, string $rewritten)
    {

        return [ 'comic' => $comic ];
    }

    /**
     * @Route(  path="/comics/{rewritten}-{id}",
     *          name="comic",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function comic(Comic $comic, string $rewritten)
    {
        if($comic->getRewritten() != $rewritten || $comic->getRouteName() != 'comic'){
            return $this->redirect($this->generateUrl($comic->getRouteName(), $comic->getRouteParams() ));
        }

        $rate = null;
        if ($this->getUser()) {
            $entityManager = $this->getDoctrine()->getManager();
            $rate = $entityManager->getRepository('App:Rate')
              ->findOneByCriteria(['user' => $this->getUser(), 'comic' => $comic]);
        }

        return [ 'comic' => $comic, 'userRate' => $rate ];
    }


    /**
     * @Route(  path="/comics/{id}/rating",
     *          name="comic_rating",
     *          requirements={"id"= "\d+"}
     *      )
     */
    public function rating(Comic $comic, Request $request, ValidatorInterface $validator)
    {
        if($this->getUser()) {
            $valRate = $request->query->get('rate');
            $entityManager = $this->getDoctrine()->getManager();

            if (null == $rate = $entityManager->getRepository('App:Rate')->findOneByCriteria(['user' => $this->getUser(), 'comic' => $comic])) {
                $rate = new Rate();
                $rate->setValue($valRate)
                  ->setUser($this->getUser())
                  ->setComic($comic);
            }

            $errors = $validator->validate($rate);

            if(count($errors) === 0) {
                $rate->setValue($valRate);
                $entityManager->persist($rate);
                $entityManager->flush();
                return $this->json(['msg' => 'ok']);
            }
        }

        return $this->json(['msg' => 'ko']);
    }

}
