<?php

namespace CoVoiturageBundle\Controller;

use CoVoiturageBundle\Entity\CoVoiturage;
use CoVoiturageBundle\Entity\CoVoiturageDays;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoVoiturageController extends Controller
{
    public function addOffreAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $co = new CoVoiturage();

        $co->setUser($this->getUser());
        $co->setType("o");

        if ($request->get("depart") == "Votre emplacement"){
            //return new Response($request->get("formattedaddr"));
            $co->setDepart($request->get("formattedaddr"));
        } else {
            $co->setDepart($request->get("depart"));
        }

        $co->setDestination($request->get("destination"));
        $co->setDepartId($request->get('idDepart'));
        $co->setDestinationId($request->get('idDestination'));

        $jours=$request->get("jour");
        if ($request->get("onetime") == "on"){
            $co->setOnetime($request->get('onetime'));
        } else {
            $co->setOnetime('off');
            $co->setDate(new \DateTime($request->get("date")));
        }
        $co->setPlacedisponibles($request->get("placesdisponibles"));

        $em->persist($co);
        $em->flush();

        if ($request->get("onetime") == "on") {
            $cod = new CoVoiturageDays();
            $cod->setIdc($co);

            foreach ($jours as $j) {
                if ($j == "lundi") $cod->setLundi('y');
                if ($j == "mardi") $cod->setMardi('y');
                if ($j == "mercredi") $cod->setMercredi('y');
                if ($j == "jeudi") $cod->setJeudi('y');
                if ($j == "vendredi") $cod->setVendredi('y');
                if ($j == "samedi") $cod->setLundi('y');
            }


            $em->persist($cod);
            $em->flush();
        }



        return $this->render('CoVoiturageBundle:Default:index.html.twig',['addsuccess' => 1]);
    }

    public function updateAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $co->setDate(new \DateTime($request->get("date")));
        $co->setHeure($request->get("heure"));
        $co->setDepart($request->get("depart"));
        $co->setDestination($request->get("destination"));
        $co->setOnetime($request->get("onetime"));
        if ($request->get("onetime") == "no"){
            $co->setPlacedisponibles($request->get("placesdisponibles"));
        }
        $em->persist($co);
        $em->flush();
    }

    public function deleteAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $em->remove($co);
        $em->flush();
    }

    public function readAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getLastThree("o");
        return $this->render('CoVoiturageBundle:Default:index.html.twig',['cov'=>$co]);
    }

    public function addOffreViewAction(Request $request){
        return $this->render('CoVoiturageBundle:Default:addoffre.html.twig');
    }
}
