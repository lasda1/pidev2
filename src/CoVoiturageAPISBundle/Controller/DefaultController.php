<?php

namespace CoVoiturageAPISBundle\Controller;

use CoVoiturageBundle\Entity\CoVoiturage;
use CoVoiturageBundle\Entity\CoVoiturageDays;
use CoVoiturageBundle\Entity\CoVoiturageRequests;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CoVoiturageAPISBundle:Default:index.html.twig');
    }

    public function getCoVoiturageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->get("type") == "o") {
            $co = $em->getRepository(CoVoiturage::class)->getAllDesc($request->get('type'));
        } else {
            $co = $em->getRepository(CoVoiturage::class)->getAllDescD($request->get('type'));
        }
        //$cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        foreach ($co as $value) {
            $value->setCreated($this->calculate_time_span($value->getUpdated()));
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['covoiturage' => $co]);
        return new JsonResponse($formatted);
    }

    public function getOwnCoVoiturageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('iduser'));
        $co = $em->getRepository(CoVoiturage::class)->getOwn($request->get('type'),$user);
        foreach ($co as $value) {
            $value->setCreated($this->calculate_time_span($value->getUpdated()));
        }
        $cor = $em->getRepository(CoVoiturageRequests::class)->findAll();
        foreach ($cor as $value) {
            $value->setCreated($this->calculate_time_span($value->getCreated()));
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['covoiturage' => $co,'covoituragerequests'=>$cor]);
        return new JsonResponse($formatted);
    }

    public function deleteCoVoiturageAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));

        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByidc($request->get("id"));
        if ($cod) {
            $em->remove($cod[0]);
        }
        if ($cor) {
            foreach ($cor as $cc){
                $em->remove($cc);
            }
        }

        $em->remove($co);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['done' => "done"]);
        return new JsonResponse($formatted);
    }

    public function getCoVoiturageRequestsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('iduser'));
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($user);
        foreach ($cor as $value) {
            $value->setCreated($this->calculate_time_span($value->getCreated()));
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['covoituragerequests' => $cor]);
        return new JsonResponse($formatted);
    }

    public function getCoVoiturageDaysAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("idc"));
        if ($cod)
            $cod = $cod[0];
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['covoituragedays' => $cod]);
        return new JsonResponse($formatted);
    }

    public function getCoVoiturageMapsAction(Request $request)
    {
        return $this->render("CoVoiturageAPISBundle:Default:MapsView.html.twig",['departid'=>$request->get("departid") , 'destinationid' => $request->get("destinationid")]);
    }

    public function getCoVoiturageAgoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get('id'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        //$date =new \DateTime("now");
        //$date->diff($co->getUpdated())->format('%m mois, %d jour')
        $formatted = $serializer->normalize(['covoiturageago' => $this->calculate_time_span($co->getUpdated())]);
        return new JsonResponse($formatted);
    }

    public function getCoVoiturageDateStringAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get('id'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['covoituragedate' => $co->getDate()->format('Y-m-d H:i:s')]);
        return new JsonResponse($formatted);
    }

    public function getCoVoiturageRequestsAgoAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturageRequests::class)->find($request->get('id'));
        $serializer = new Serializer([new ObjectNormalizer()]);
        //$date =new \DateTime("now");
        //$date->diff($co->getUpdated())->format('%m mois, %d jour')
        $formatted = $serializer->normalize(['requestago' => $this->calculate_time_span($co->getCreated())]);
        return new JsonResponse($formatted);
    }

    public function getCoVoiturageOwnRequestsAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('id'));
        $co = $em->getRepository(CoVoiturageRequests::class)->getOwn($user);
        if ($co){
            $co = "yes";
        } else {
            $co = "no";
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['ownrequests' => $co ]);
        return new JsonResponse($formatted);
    }

    public function addRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $cor = new CoVoiturageRequests();
        $user = $em->getRepository(User::class)->find($request->get("iduser"));
        $cor->setUser($user);
        $cor->setIdc($co);
        $cor->setEtat("a");
        $em->persist($cor);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['done' => "done"]);
        return new JsonResponse($formatted);

    }

    public function calculate_time_span($date){
        $seconds  = strtotime(date('Y-m-d H:i:s')) - $date->getTimestamp();


        $months = floor($seconds / (3600*24*30));
        $day = floor($seconds / (3600*24));
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours*3600)) / 60);
        $secs = floor($seconds % 60);

        if($seconds < 60)
            $time = $secs." seconds ago";
        else if($seconds < 60*60 )
            $time = $mins." min ago";
        else if($seconds < 24*60*60)
            $time = $hours." hours ago";
        else if($seconds < 24*60*60*30)
            $time = $day." day ago";
        else
            $time = $months." month ago";

        return $time;
    }

    public function acceptRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get("id"));
        $co = $co = $em->getRepository(CoVoiturage::class)->find($cor->getIdc());
        if ($co->getType() == "o") {
            if ($co->getPlacedisponibles() > 0) {
                $co->setPlacedisponibles($co->getPlacedisponibles() - 1);
                $cor->setEtat("c");
                $em->flush();
            }
        } else {
            $cor->setEtat("c");
            $em->flush();
        }
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['done' => "done"]);
        return new JsonResponse($formatted);
    }

    public function declineRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get("id"));
        $co = $co = $em->getRepository(CoVoiturage::class)->find($cor->getIdc());
        $cor->setEtat("r");
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['done' => "done"]);
        return new JsonResponse($formatted);

    }

    public function deleteRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get('id'));
        $em->remove($cor);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['done' => "done"]);
        return new JsonResponse($formatted);
    }


}
