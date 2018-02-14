<?php

namespace EspaceEtudeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EspaceEtudeBundle:Default:index.html.twig');
    }
}
