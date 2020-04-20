<?php

namespace App\Controller;

use App\Entity\Box;
use App\Form\ComicType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Comic;

class ComicController extends Controller
{

    /**
     * @Route(  path="/{rewritten}-{id}",
     *          name="comicRedirect",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function comicRedirectAction(Comic $comic, $rewritten)
    {
        $url = $this->generateUrl('comic', $comic->getRouteParams());
        return $this->redirect($url);
    }

    /**
     * @Route(  path="/comics/{rewritten}-{id}",
     *          name="comic",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function comicAction(Comic $comic, $rewritten)
    {
        if($comic->getRewritten() != $rewritten){
            return $this->redirect($this->generateUrl('comic', $comic->getRouteParams() ));
        }

        if(!$comic->getOnline()) {
            return $this->redirect($this->generateUrl('homepage'));
        }


        return [ 'comic' => $comic, 'votes' => [] ];
    }
}
