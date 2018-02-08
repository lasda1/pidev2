<?php

namespace CoVoiturageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoVoiturageController extends Controller
{
    public function addAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $co = new CoVoiturage();
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
        $co = $em->getRepository(CoVoiturage::class)->findAll();
    }
}
