<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        
        $user = $this->getUser();
        return $this->render('@User/layout.html.twig',['user' => $user]);
    }
}
