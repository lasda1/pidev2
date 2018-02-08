<?php

namespace CoVoiturageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CoVoiturageBundle:Default:index.html.twig');
    }
}
