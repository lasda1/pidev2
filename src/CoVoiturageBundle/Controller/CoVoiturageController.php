<?php

namespace CoVoiturageBundle\Controller;

use CoVoiturageBundle\Entity\CoVoiturage;
use CoVoiturageBundle\Entity\CoVoiturageDays;
use CoVoiturageBundle\Entity\CoVoiturageRequests;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoVoiturageController extends Controller
{
    public function addOffreAction(Request $request)
    {
        if ($request->get("depart") == null || $request->get("destination") == null || $request->get("placesdisponibles") == null) {
            return $this->render('CoVoiturageBundle:Default:addoffre.html.twig' ,['success' => 1]);
        } else if ($request->get('idDepart') == null || $request->get('idDestination') == null ){
            return $this->render('CoVoiturageBundle:Default:addoffre.html.twig' ,['success' => 2]);
        } else if ($request->get("onetime") != "on" && ! $request->get("date")){
            return $this->render('CoVoiturageBundle:Default:addoffre.html.twig' ,['success' => 3]);
        }


        $em = $this->getDoctrine()->getManager();
        $co = new CoVoiturage();

        $co->setUser($this->getUser());
        $co->setType("o");

        if ($request->get("depart") == "Votre emplacement") {
            //return new Response($request->get("formattedaddr"));
            $co->setDepart($request->get("formattedaddr"));
        } else {
            $co->setDepart($request->get("depart"));
        }

        $co->setDestination($request->get("destination"));
        $co->setDepartId($request->get('idDepart'));
        $co->setDestinationId($request->get('idDestination'));
        $co->setDepartLat($request->get('latDepart'));
        $co->setDepartLng($request->get('lngDepart'));

        $jours = $request->get("jour");

        if ($request->get("onetime") == "on") {
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


        return $this->redirectToRoute('co_voiturage_viewoffreparam', ['success' => 1]);
        //return $this->render('CoVoiturageBundle:Default:index.html.twig',['addsuccess' => 1]);
    }


    public function addDemandeAction(Request $request)
    {

        if ($request->get("depart") == null || $request->get("destination") == null) {
            return $this->render('CoVoiturageBundle:Default:adddemande.html.twig' ,['success' => 1]);
        } else if ($request->get('idDepart') == null || $request->get('idDestination') == null ){
            return $this->render('CoVoiturageBundle:Default:adddemande.html.twig' ,['success' => 2]);
        } else if ($request->get("onetime") != "on" && ! $request->get("date")){
            return $this->render('CoVoiturageBundle:Default:adddemande.html.twig' ,['success' => 3]);
        }

        $em = $this->getDoctrine()->getManager();
        $co = new CoVoiturage();

        $co->setUser($this->getUser());
        $co->setType("d");

        if ($request->get("depart") == "Votre emplacement") {
            //return new Response($request->get("formattedaddr"));
            $co->setDepart($request->get("formattedaddr"));
        } else {
            $co->setDepart($request->get("depart"));
        }

        $co->setDestination($request->get("destination"));
        $co->setDepartId($request->get('idDepart'));
        $co->setDestinationId($request->get('idDestination'));
        $co->setDepartLat($request->get('latDepart'));
        $co->setDepartLng($request->get('lngDepart'));

        $jours = $request->get("jour");
        if ($request->get("onetime") == "on") {
            $co->setOnetime($request->get('onetime'));
        } else {
            $co->setOnetime('off');
            $co->setDate(new \DateTime($request->get("date")));
        }

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


        return $this->redirectToRoute('co_voiturage_viewdemandeparam', ['success' => 1]);
        //return $this->render('CoVoiturageBundle:Default:index.html.twig',['addsuccess' => 1]);
    }

    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $co->setDate(new \DateTime($request->get("date")));
        $co->setHeure($request->get("heure"));
        $co->setDepart($request->get("depart"));
        $co->setDestination($request->get("destination"));
        $co->setOnetime($request->get("onetime"));
        if ($request->get("onetime") == "no") {
            $co->setPlacedisponibles($request->get("placesdisponibles"));
        }
        $em->persist($co);
        $em->flush();
    }

    public function deleteOffreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));

        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByidc($request->get("id"));
        if ($cod) {
            $em->remove($cod[0]);
        }
        if ($cor){
            $em->remove($cor);
        }

        $em->remove($co);
        $em->flush();
        return $this->redirectToRoute('co_voiturage_viewoffreparam', ['success' => 3]);
    }

    public function deleteDemandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));

        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByidc($request->get("id"));

        if ($cod) {
            $em->remove($cod[0]);
        }
        if ($cor){
            $em->remove($cor);
        }

        $em->remove($co);
        $em->flush();
        return $this->redirectToRoute('co_voiturage_viewdemandeparam', ['success' => 3]);
    }

    public function readAction(Request $request)
    {
        return $this->render('CoVoiturageBundle:Default:index.html.twig');
    }

    public function addOffreViewAction()
    {
        return $this->render('CoVoiturageBundle:Default:addoffre.html.twig');
    }

    public function addDemandeViewAction()
    {
        return $this->render('CoVoiturageBundle:Default:adddemande.html.twig');
    }

    public function viewOffreAction()
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getAllDesc('o');
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        if ($cor) {
            return $this->render('CoVoiturageBundle:Default:viewoffre.html.twig', ['cov' => $co, 'cor' => $cor[0]]);
        }
        return $this->render('CoVoiturageBundle:Default:viewoffre.html.twig', ['cov' => $co, 'cor' => null]);

    }

    public function viewOffrePaginateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getAllDesc('o');
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $co, /* query NOT result */
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 4));
        if ($cor) {
            $cor = $cor[0];
        } else {
            $cor = null;
        }

        return $this->render('CoVoiturageBundle:Default:viewoffre.html.twig', ['cov' => $co,'cor' => $cor, 'pagination' => $pagination]);
    }

    public function viewDemandeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getAllDescD('d');
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        if ($cor) {
            return $this->render('CoVoiturageBundle:Default:viewdemande.html.twig', ['cov' => $co, 'cor' => $cor[0]]);
        }
        return $this->render('CoVoiturageBundle:Default:viewdemande.html.twig', ['cov' => $co, 'cor' => null]);

    }

    public function viewDemandePaginateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getAllDescD('d');
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $co, /* query NOT result */
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 4));
        if ($cor) {
            $cor = $cor[0];
        } else {
            $cor = null;
        }
        return $this->render('CoVoiturageBundle:Default:viewdemande.html.twig', ['cov' => $co,'cor' => $cor, 'pagination' => $pagination]);

        //return $this->render('CoVoiturageBundle:Default:viewdemande.html.twig', ['cov' => $co, 'cor' => null]);

    }

    public function viewOwnOffreAction()
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getOwn('o', $this->getUser());
        $cor = $em->getRepository(CoVoiturageRequests::class)->getOrderedBy();

        $co2 = $em->getRepository(CoVoiturage::class)->findAll();
        $cor2 = $em->getRepository(CoVoiturageRequests::class)->findByUser($this->getUser());

        $cor22 = $cor2;

        foreach ($cor2 as $c){
            if ($c->getEtat() == "r"){
                $em->remove($c);
                $em->flush();
            }
        }


        return $this->render('CoVoiturageBundle:Default:viewownoffre.html.twig', ['cov' => $co, 'cor' => $cor, 'cov2' => $co2, 'cor2' => $cor22]);
    }

    public function viewOwnDemandeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getOwn('d', $this->getUser());
        $cor = $em->getRepository(CoVoiturageRequests::class)->getOrderedBy();

        $co2 = $em->getRepository(CoVoiturage::class)->findAll();
        $cor2 = $em->getRepository(CoVoiturageRequests::class)->findByUser($this->getUser());

        $cor22 = $cor2;

        foreach ($cor2 as $c){
            if ($c->getEtat() == "r"){
                $em->remove($c);
                $em->flush();
            }
        }

        return $this->render('CoVoiturageBundle:Default:viewowndemande.html.twig', ['cov' => $co, 'cor' => $cor, 'cov2' => $co2, 'cor2' => $cor22]);
    }

    public function viewOffreParamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getAllDesc('o');
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $co, /* query NOT result */
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 4));
        if ($cor) {
            $cor = $cor[0];
        } else {
            $cor = null;
        }
        return $this->render('CoVoiturageBundle:Default:viewoffre.html.twig', ['cov' => $co, 'cor' => $cor ,'pagination' => $pagination, 'success' => $request->get('success')]);
    }

    public function viewDemandeParamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getAllDescD('d');
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $co, /* query NOT result */
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 4));
        if ($cor) {
            $cor = $cor[0];
        } else {
            $cor = null;
        }
        return $this->render('CoVoiturageBundle:Default:viewdemande.html.twig', ['cov' => $co, 'cor' => $cor,'pagination' => $pagination, 'success' => $request->get('success')]);
    }




    public function modifyOffreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));

        if ($request->get("depart") == "Votre emplacement") {
            //return new Response($request->get("formattedaddr"));
            $co->setDepart($request->get("formattedaddr"));
        } else {
            $co->setDepart($request->get("depart"));
        }

        $co->setDestination($request->get("destination"));
        $co->setDepartId($request->get('idDepart'));
        $co->setDestinationId($request->get('idDestination'));

        $jours = $request->get("jour");
        if ($request->get("onetime") == "on") {
            $co->setOnetime($request->get('onetime'));
        } else {
            $co->setOnetime('off');
            $co->setDate(new \DateTime($request->get("date")));
        }
        $co->setPlacedisponibles($request->get("placesdisponibles"));

        $em->flush();


        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));

        if ($cod)
            $cod = $cod[0];

        if ($request->get("onetime") == "on" && $cod == null) {
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
        } else if ($request->get("onetime") == "on" && $cod != null) {
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


            $em->flush();
        } else if ($request->get("onetime") != "on" && $cod != null) {
            $em->remove($cod);
            $em->flush();
        }


        //return $this->render('CoVoiturageBundle:Default:index.html.twig',['modifysuccess' => 1]);
        return $this->redirectToRoute('co_voiturage_viewoffreparam', ['success' => 2]);
    }

    public function modifyDemandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));

        if ($request->get("depart") == "Votre emplacement") {
            //return new Response($request->get("formattedaddr"));
            $co->setDepart($request->get("formattedaddr"));
        } else {
            $co->setDepart($request->get("depart"));
        }

        $co->setDestination($request->get("destination"));
        $co->setDepartId($request->get('idDepart'));
        $co->setDestinationId($request->get('idDestination'));

        $jours = $request->get("jour");
        if ($request->get("onetime") == "on") {
            $co->setOnetime($request->get('onetime'));
        } else {
            $co->setOnetime('off');
            $co->setDate(new \DateTime($request->get("date")));
        }

        $em->flush();


        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));

        if ($cod)
            $cod = $cod[0];

        if ($request->get("onetime") == "on" && $cod == null) {
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
        } else if ($request->get("onetime") == "on" && $cod != null) {
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


            $em->flush();
        } else if ($request->get("onetime") != "on" && $cod != null) {
            $em->remove($cod);
            $em->flush();
        }


        //return $this->render('CoVoiturageBundle:Default:index.html.twig',['modifysuccess' => 1]);
        return $this->redirectToRoute('co_voiturage_viewdemandeparam', ['success' => 2]);
    }

    public function modifyOffreViewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        if ($cod)
            return $this->render('CoVoiturageBundle:Default:modifyoffre.html.twig', ['co' => $co, 'cod' => $cod[0]]);
        else {
            return $this->render('CoVoiturageBundle:Default:modifyoffre.html.twig', ['co' => $co, 'cod' => null]);
        }
    }

    public function modifyDemandeViewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        if ($cod)
            return $this->render('CoVoiturageBundle:Default:modifydemande.html.twig', ['co' => $co, 'cod' => $cod[0]]);
        else {
            return $this->render('CoVoiturageBundle:Default:modifydemande.html.twig', ['co' => $co, 'cod' => null]);
        }
    }

    public function infoOffreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        if ($cod) {
            $cod = $cod[0];
        } else {
            $cod = null;
        }

        if ($cor) {
            $cor = $cor[0];
        } else {
            $cor = null;
        }


        return $this->render('CoVoiturageBundle:Default:infooffre.html.twig', ['co' => $co, 'cod' => $cod , 'cor' =>$cor]);
    }

    public function infoDemandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        if ($cod) {
            $cod = $cod[0];
        } else {
            $cod = null;
        }

        if ($cor) {
            $cor = $cor[0];
        } else {
            $cor = null;
        }

        return $this->render('CoVoiturageBundle:Default:infodemande.html.twig', ['co' => $co, 'cod' => $cod , 'cor' =>$cor]);
    }

    public function requestOffreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        if ($co->getType() == "o") {
            if ($co->getPlacedisponibles() && $co->getPlacedisponibles() > 0) {
                //$co->setPlacedisponibles($co->getPlacedisponibles() - 1);
                $cor = new CoVoiturageRequests();
                $cor->setUser($this->getUser());
                $cor->setIdc($co);
                $cor->setEtat("a");
                $em->persist($cor);
                $em->flush();
                return $this->redirectToRoute('co_voiturage_viewoffreparam', ['success' => 4]);
            } else {
                //$co->setPlacedisponibles($co->getPlacedisponibles() - 1);
                return $this->redirectToRoute('co_voiturage_viewoffreparam', ['success' => 5]);
            }
        }
    }

    public function requestDemandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        if ($co->getType() == "d") {
                //$co->setPlacedisponibles($co->getPlacedisponibles() - 1);
                $cor = new CoVoiturageRequests();
                $cor->setUser($this->getUser());
                $cor->setIdc($co);
                $cor->setEtat("a");
                $em->persist($cor);
                $em->flush();
                return $this->redirectToRoute('co_voiturage_viewdemandeparam', ['success' => 4]);
        }
    }


    public function acceptOffreRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get("id"));
        $co = $co = $em->getRepository(CoVoiturage::class)->find($cor->getIdc());
        if ($co->getType() == "o") {
            if ($co->getPlacedisponibles() == 0) {
                return $this->redirectToRoute('co_voiturage_viewownoffreparam', ['success' => 3]);
            }
            $co->setPlacedisponibles($co->getPlacedisponibles() - 1);
            $cor->setEtat("c");
            $em->flush();
            return $this->redirectToRoute('co_voiturage_viewownoffreparam', ['success' => 1]);
        }
    }

    public function acceptDemandeRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get("id"));
        $co = $co = $em->getRepository(CoVoiturage::class)->find($cor->getIdc());
        if ($co->getType() == "d") {
            $cor->setEtat("c");
            $em->flush();
            return $this->redirectToRoute('co_voiturage_viewowndemandeparam', ['success' => 1]);
        }
    }

    public function refuseOffreRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get("id"));
        $co = $co = $em->getRepository(CoVoiturage::class)->find($cor->getIdc());
        if ($co->getType() == "o") {
            $cor->setEtat("r");
            $em->flush();
            return $this->redirectToRoute('co_voiturage_viewownoffreparam', ['success' => 5]);
        }
    }

    public function refuseDemandeRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get("id"));
        $co = $co = $em->getRepository(CoVoiturage::class)->find($cor->getIdc());
        if ($co->getType() == "d") {
            $cor->setEtat("r");
            $em->flush();
            return $this->redirectToRoute('co_voiturage_viewowndemandeparam', ['success' => 5]);
        }
    }

    public function viewOwnOffreParamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getOwn('o', $this->getUser());
        $cor = $em->getRepository(CoVoiturageRequests::class)->getOrderedBy();

        $co2 = $em->getRepository(CoVoiturage::class)->findAll();
        $cor2 = $em->getRepository(CoVoiturageRequests::class)->findByUser($this->getUser());

        return $this->render('CoVoiturageBundle:Default:viewownoffre.html.twig', ['cov' => $co, 'cor' => $cor, 'cov2' => $co2, 'cor2' => $cor2, 'success' => $request->get('success')]);

    }

    public function viewOwnDemandeParamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getOwn('d', $this->getUser());
        $cor = $em->getRepository(CoVoiturageRequests::class)->getOrderedBy();

        $co2 = $em->getRepository(CoVoiturage::class)->findAll();
        $cor2 = $em->getRepository(CoVoiturageRequests::class)->findByUser($this->getUser());

        return $this->render('CoVoiturageBundle:Default:viewowndemande.html.twig', ['cov' => $co, 'cor' => $cor, 'cov2' => $co2, 'cor2' => $cor2, 'success' => $request->get('success')]);

    }


    public function deleteOffreRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get('id'));
        $em->remove($cor);
        $em->flush();
        return $this->redirectToRoute('co_voiturage_viewownoffreparam', ['success' => 4]);
    }

    public function deleteDemandeRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get('id'));
        $em->remove($cor);
        $em->flush();
        return $this->redirectToRoute('co_voiturage_viewowndemandeparam', ['success' => 4]);
    }
}
