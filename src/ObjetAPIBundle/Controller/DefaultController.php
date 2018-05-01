<?php

namespace ObjetAPIBundle\Controller;

use ObjetBundle\Entity\Interaction;
use ObjetBundle\Entity\Objet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UserBundle\Entity\User;

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

   /* public function addInteractionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->find($request->get("id"));
        $interaction = new Interaction();
        $user = $em->getRepository(User::class)->find($request->get("iduser"));
        $interaction->setIdUser($user);
        $interaction->setIdObjet($objet);
        $interaction->setStatut("c'est reclamé");
        $em->persist($interaction);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['done' => "done"]);
        return new JsonResponse($formatted);

    }*/
    public function reclamerObjAction($id,$iduser)
    {
        $em = $this->getDoctrine()->getManager();
        $us = $em->getRepository(User::class)->find($iduser);
        $nom=$us->getNom();
        $objet = $em->getRepository(Objet::class)->find($id);
        $inter = $this->getDoctrine()->getRepository(Interaction::class)->findtrouver($id);
        if($inter == null)
        {
            if ($objet->getNature() == "Objet Perdu") {

                $inter = new Interaction();
                $inter->setIdUser($us);
                $inter->setIdObjet($objet);
                $inter->setStatut('Cet objet perdu a été réclamé comme trouvé par ' .$nom);
                $em->persist($inter);
                $em->flush();
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize(['ReclamObjPerd' => $inter]);
                return new JsonResponse($formatted);

            }
            else
            {


                $inter = new Interaction();
                $inter->setIdUser($us);
                $inter->setIdObjet($objet);
                $inter->setStatut($nom.' est le propriétaire de Cet Objet');
                $em->persist($inter);
                $em->flush();
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize(['ReclamObjTrouv' => $inter]);
                return new JsonResponse($formatted);

            }
        }
        else
        {
            $msg='deja reclame par '.$nom ;
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize(['Reclam' => $msg]);
            return new JsonResponse($formatted);
        }
    }

    public function supprimerInteractionAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $interaction = $em->getRepository(Interaction::class)->findByidObjet($id);
        $em->remove($interaction[0]);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize(['Supprimer Interaction' => $interaction]);
        echo 'Reclamation supprimé';
        return new JsonResponse($formatted);

    }
}
