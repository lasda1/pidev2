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
        $co = $em->getRepository(CoVoiturage::class)->getAllDesc($request->get('type'));
        //$cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($this->getUser());
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['covoiturage' => $co]);
        return new JsonResponse($formatted);
    }

    public function getCoVoiturageRequestsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($request->get('iduser'));
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByuser($user);
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


}
