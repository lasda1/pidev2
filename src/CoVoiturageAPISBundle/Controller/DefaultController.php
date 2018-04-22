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

}
