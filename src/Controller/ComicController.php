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
     * @Route(  path="/comics/{rewritten}-{id}/screen-fb",
     *          name="comic_screen_fb",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function comicScreenFbAction(Comic $comic, $rewritten)
    {

        return [ 'comic' => $comic, 'votes' => [] ];
    }

    /**
     * @Route(  path="/comics/{rewritten}-{id}/screen",
     *          name="comic_screen",
     *          requirements={"rewritten"="[a-z0-9-]+", "id"= "\d+"}
     *      )
     * @Template
     */
    public function comicScreenAction(Comic $comic, $rewritten)
    {

        return [ 'comic' => $comic, 'votes' => [] ];
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

        return [ 'comic' => $comic, 'votes' => [] ];
    }
}
