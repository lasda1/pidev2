<?php

namespace EspaceEtudeBundle\Controller;

use EspaceEtudeBundle\Entity\Documents;
use EspaceEtudeBundle\Entity\Matiere;
use EspaceEtudeBundle\Entity\notification;
use EspaceEtudeBundle\Entity\Section;
use EspaceEtudeBundle\Enum\Niveau;
use Org_Heigl\Ghostscript\Ghostscript;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        if ($user) {

        $em=$this->getDoctrine()->getManager();
        $notifCoi=$em->getRepository(notification::class)->findAllCroi();
        $notifAll=$em->getRepository(notification::class)->countAll();
        $niveau=new Niveau();
        $niveau=$niveau->getAvailableTypes();
        $section=$em->getRepository(Section::class)->findAll();

        return $this->render('@EspaceEtude/layout.html.twig',array('niveaux'=>$niveau,'sections'=>$section,'notifAll'=>$notifAll,'notifcroi'=>$notifCoi
        ));
        }
        return $this->redirectToRoute('fos_user_security_login');
    }

    public function getAllSectionAction($class)
    {
        $response = new Response();
        $em=$this->getDoctrine()->getManager();
        $section=$em->getRepository(Section::class)->findby([
            'niveau' => $class,
        ]);
        $response->headers->set("Content-Type","application/json");
        $sectionArray=array();
        foreach ($section as $s){

                    $a = array(
                        "id" => $s->getId(),
                        "libelle" => $s->getLibelle(),
                        "niveau" => $s->getNiveau()


            );
           array_push($sectionArray,$a);
        };
        $response->setContent(json_encode( array(
            "sections" => $sectionArray)));






        return $response;
    }
    public function getAllMatiereAction($id)
    {
        $response = new Response();
        $em=$this->getDoctrine()->getManager();
        $section=$em->getRepository(Section::class)->findOneBy(array('id' =>$id));
        $matiere=$section->getMatiere();

        $response->headers->set("Content-Type","application/json");
        $sectionArray=array();
        foreach ($matiere as $s){

            $a = array(
                "id" => $s->getId(),
                "libelle" => $s->getLibelle(),
                "coefficient" => $s->getCoefficient(),
                "type"=> $s->getType()


            );
            array_push($sectionArray,$a);
        };
        $response->setContent(json_encode( array(
            "matieres" => $sectionArray)));






        return $response;
    }
    public function getAllDocumentsAction($id)
    {
        $response = new Response();
        $em=$this->getDoctrine()->getManager();
        $documents=$em->getRepository(Documents::class)->findBy( ['matiere' => $id,'flag' => 1]);


        $response->headers->set("Content-Type","application/json");
        $sectionArray=array();
        foreach ($documents as $d){

            $a = array(
                "id" => $d->getId(),
                "libelle" => $d->getLibelle(),
                "path" => $d->getPath(),
                "date"=> $d->getDate(),
                "type"=> $d->getTypeDocument(),
                "size" => $d->getSize(),
                "matiere"=> $d->getMatiere()->getId(),
                "user"=> $d->getUser()->getId()
            );
            array_push($sectionArray,$a);
        };
        $response->setContent(json_encode( array(
            "documents" => $sectionArray)));






        return $response;
    }
    public function setNotificationVuAction($id)
    {
        $response = new Response();
        $em=$this->getDoctrine()->getManager();
        $notif=$em->getRepository(notification::class)->find($id);
        $notif=$notif->setVu("1");
        $em->persist($notif);
        $em->flush();

        $response->headers->set("Content-Type","application/json");

        $response->setContent(json_encode( array(
            "notif" => array(
                'idNotif' => $notif->getId(),
                'id' => $notif->getVu()
            ))));






        return $response;
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
