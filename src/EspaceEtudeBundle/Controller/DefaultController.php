<?php

namespace EspaceEtudeBundle\Controller;

use EspaceEtudeBundle\Entity\notification;
use EspaceEtudeBundle\Entity\Section;
use EspaceEtudeBundle\Enum\Niveau;
use Org_Heigl\Ghostscript\Ghostscript;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        if ($user) {

        $em=$this->getDoctrine()->getManager();
        $notif=$em->getRepository(notification::class)->findAll();
        $notifAll=$em->getRepository(notification::class)->countAll();
        $niveau=new Niveau();
        $niveau=$niveau->getAvailableTypes();
        $section=$em->getRepository(Section::class)->findAll();

        return $this->render('@EspaceEtude/layout.html.twig',array('niveaux'=>$niveau,'sections'=>$section,'notifs'=>$notif,'notifAll'=>$notifAll
        ));
        }
        return $this->redirectToRoute('fos_user_security_login');
    }


    public function testAction()
    {

        return $this->render('@EspaceEtude/Default/index.html.twig');
    }
    public function rechercheAction(Request $request){
        if($request->isXmlHttpRequest()){
            $docName=$request->get('docName');
            $em=$this->getDoctrine()->getManager();
            $docs=$em->getRepository('EspaceEtudeBundle\Entity\Documents')->findAll();
            $serializer=new Serializer(array(new ObjectNormalizer()));
            //n7otou el data
            $data=$serializer->normalize($docs);
            return new JsonResponse($data);

        }
    }
    
    public function updateNotifAction(Request $request){
        if($request->isXmlHttpRequest()){
            $id=$request->get('id');
            $em=$this->getDoctrine()->getManager();
            $notif=$em->getRepository(notification::class)->find($id);
            $notif=$notif->setVu("1");
            $em->persist($notif);
            $em->flush();
            return new JsonResponse("a");

        }
    }


}
