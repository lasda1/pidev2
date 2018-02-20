<?php

namespace EspaceEtudeBundle\Controller;

use EspaceEtudeBundle\Entity\Matiere;
use EspaceEtudeBundle\Enum\Niveau;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EspaceEtudeBundle\Entity\Section;

class MatieresController extends Controller
{
    public function afficherMatiereAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager();
        $niveau=new Niveau();
        $niveau=$niveau->getAvailableTypes();
        $section=$em->getRepository(Section::class)->findAll();
        $matiere=$em->getRepository(Section::class)->findOneBy(array('id' =>$request->attributes->get('section')));
        $matiere=$matiere->getMatiere();
        return $this->render('EspaceEtudeBundle:Matieres:afficher_matiere.html.twig', array('niveaux'=>$niveau,'sections'=>$section,
            'matieres'=>$matiere
        ));
    }

}
