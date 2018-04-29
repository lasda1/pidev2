<?php

namespace ObjetAPIBundle\Controller;

use ObjetBundle\Entity\Objet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ObjetAPIBundle:Default:index.html.twig');
    }

    public function getObjTrouvAction()
    {
        $em = $this->getDoctrine()->getManager();
        $objtrouv = $em->getRepository(Objet::class)->objtrouv();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['ObjTrouv' => $objtrouv]);
        return new JsonResponse($formatted);
    }

    public function getObjPerdAction()
    {
        $em = $this->getDoctrine()->getManager();
        $objperd = $em->getRepository(Objet::class)->objperd();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['ObjPerd' => $objperd]);
        return new JsonResponse($formatted);
    }
}
