<?php

namespace EspaceEtudeBundle\Controller;

use EspaceEtudeBundle\Entity\Matiere;
use EspaceEtudeBundle\Entity\notification;
use EspaceEtudeBundle\Enum\Niveau;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EspaceEtudeBundle\Entity\Section;

class MatieresController extends Controller
{


    public function afficherMatiereAction(Request $request)
    {
        $user = $this->getUser();
        if ($user) {
        $em=$this->getDoctrine()->getManager();
            $notif=$em->getRepository(notification::class)->findAll();
            $notifAll=$em->getRepository(notification::class)->countAll();
        $section=$em->getRepository(Section::class)->findOneBy(array('id' =>$request->attributes->get('section')));
        $matiere=$section->getMatiere();
        return $this->render('EspaceEtudeBundle:Matieres:afficher_matiere.html.twig', array(
            'matieres'=>$matiere,'notifs'=>$notif,'notifAll'=>$notifAll
        ));
        }
        return $this->redirectToRoute('fos_user_security_login');
    }

    public function afficherSectionAction(Request $request){
        $user = $this->getUser();
        if ($user) {

            $em=$this->getDoctrine()->getManager();

            $notif=$em->getRepository(notification::class)->findAll();
            $notifAll=$em->getRepository(notification::class)->countAll();
            $section=$em->getRepository(Section::class)->findAll();
            return $this->render('EspaceEtudeBundle:Matieres:afficher_section.html.twig', array(
                'sections'=>$section,'niveau'=>$request->attributes->get('niveau'),'notifs'=>$notif,'notifAll'=>$notifAll
            ));
        }
        return $this->redirectToRoute('fos_user_security_login');
        }
}
