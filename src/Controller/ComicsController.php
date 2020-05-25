<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ComicsController extends Controller
{
    
    /**
     * @Route(  path="/comics/",
     *          name="comics"
     *      ),
     * @Route(  path="/comics/{page}/",
     *          name="comics_page",
     *          requirements={"page"= "\d+"}
     *      )
     * @Template
     */
    public function comics(int $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $page -1;

        $nb = $em->getRepository('App:Comic')->findByParams(['publish' => true, 'get_count' => 1]);

        if ($page == 0) {
            $limit = 35;
            $offset = 0;
        } else {
            $limit = 40;
            $offset = 35 + (($page-1)*$limit);
        }

        $maxPage = ceil((($nb-35)/40)+1);
        $comics = $em->getRepository('App:Comic')->findByParams(['publish' => true, 'offset' => $offset, 'limit' => $limit]);

        return [ 'comics' => $comics , 'currentPage' => $page+1, 'maxPage' => $maxPage ];
    }

    /**
     * @Route(  path="/comics/bds-du-jour/",
     *          name="day_comics"
     *      )
     * @Template
     */
    public function dayComics()
    {
        $em = $this->getDoctrine()->getManager();

        $comics = $em->getRepository('App:Comic')->findByParams(['selected' => 1, 'orderBy' => 'dateSelected']);

        return [ 'comics' => $comics ];
    }
}
