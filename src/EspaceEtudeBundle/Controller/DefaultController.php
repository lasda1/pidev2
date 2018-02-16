<?php

namespace EspaceEtudeBundle\Controller;

use EspaceEtudeBundle\Entity\Section;
use EspaceEtudeBundle\Enum\Niveau;
use Org_Heigl\Ghostscript\Ghostscript;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em=$this->getDoctrine()->getManager();
        $niveau=new Niveau();
        $niveau=$niveau->getAvailableTypes();
        $section=$em->getRepository(Section::class)->findAll();

        return $this->render('@EspaceEtude/layout.html.twig',array('niveaux'=>$niveau,'sections'=>$section
        ));
    }


    public function testAction()
    {

        return $this->render('@EspaceEtude/Default/index.html.twig');
    }


}
