<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomepageController
{
    /**
     * @Route(  path="/",
     *          name="homepage"
     *      )
     * @Template
     */
    public function homeAction()
    {

        return  [];
    }
}
