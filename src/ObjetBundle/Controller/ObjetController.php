<?php

namespace ObjetBundle\Controller;

use DateInterval;
use DatePeriod;
use DateTime;
use Ob\HighchartsBundle\Highcharts\Highchart;
use ObjetBundle\Entity\Interaction;
use ObjetBundle\Entity\Objet;
use ObjetBundle\Form\ObjetType;
use ObjetBundle\Repository\ObjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ObjetController extends Controller
{
    public function ajoutobjtrouvAction(Request $request, UserInterface $username)
    {
        $objet = new Objet();
        $form = $this->createForm(ObjetType::class, $objet);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $file = $objet->getPhoto();

            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $path = "C:/xampp/htdocs/pidev2/web";

            $file->move(
                $path,
                $fileName
            );
            $username = $this->container->get('security.token_storage')->getToken()->getUser();
            $objet->setNature('Objet Trouvé');
            $objet->setUser($username);
            $objet->setPhoto($fileName);
            $objet = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($objet);
            $em->flush();
            return $this->redirectToRoute("affichobjtrouv");
        }


        return $this->render('ObjetBundle:Objet:ajoutobj.html.twig', array('form' => $form->createView()
            // ...
       ,['success' => 1] ));
    }

    public function ajoutobjperdAction(Request $request, UserInterface $username)
    {
        $objet = new Objet();
        $form = $this->createForm(ObjetType::class, $objet);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $file = $objet->getPhoto();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $path = "C:/xampp/htdocs/pidev2/web";
            $file->move(
                $path,
                $fileName
            );
            $username = $this->container->get('security.token_storage')->getToken()->getUser();
            $objet->setNature('Objet Perdu');
            $objet->setUser($username);
            $objet->setPhoto($fileName);
            $objet = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($objet);
            $em->flush();
            return $this->redirectToRoute("affichobjperd");
        }


        return $this->render('ObjetBundle:Objet:ajoutobj.html.twig', array('form' => $form->createView()
            // ...
        ));
    }

    public function affichobjtrouvAction()
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->objtrouv();
        $interaction=$em->getRepository(Interaction::class)->findAll();

        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet' => $objet, 'nature' => 1,'interaction'=>$interaction
            // ...
        ));
    }

    public function affichobjperdAction()
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->objperd();
        $interaction=$em->getRepository(Interaction::class)->findAll();
        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet' => $objet, 'nature' => 2,'interaction'=>$interaction
            // ...
        ));
    }


    public function deleteobjAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->find($id);
        $em->remove($objet);
        $em->flush();
        if ($objet->getNature() == 'Objet Perdu') {
            return $this->redirectToRoute("affichobjperd");
        } else {
            return $this->redirectToRoute("affichobjtrouv");
        }
    }

    public function updateobjAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->find($id);
        $Form = $this->createForm(ObjetType::class, $objet);
        $Form->handleRequest($request);
        if ($Form->isValid() && $Form->isSubmitted()) {
            $file = $objet->getPhoto();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $path = "C:/xampp/htdocs/pidev2/web";
            $file->move(
                $path,
                $fileName
            );
            $username = $this->container->get('security.token_storage')->getToken()->getUser();
            $objet->setUser($username);
            $objet->setPhoto($fileName);
            $em->persist($objet);
            $em->flush();
            if ($objet->getNature() == 'Objet Perdu') {
                return $this->redirectToRoute("affichobjperd");
            } else {
                return $this->redirectToRoute("affichobjtrouv");
            }

        }
        return $this->render('ObjetBundle:Objet:ajoutobj.html.twig', array('form' => $Form->createView()            // ...
        ));

    }

    public function objetAction()
    {
        $em = $this->getDoctrine()->getManager();
        $objets = $em->getRepository(Objet::class)->objtrouv();


        usort($objets, function ($a, $b)
        {
            return ($a->getDate() > $b->getDate());
        });
        $period = new DatePeriod(
            (new DateTime($objets[0]->getDate()->format('Y-m-d'))),
            new DateInterval('P1D'),
            (new DateTime($objets[count($objets)-1]->getDate()->format('Y-m-d')))
        );
        $dates = array();
        foreach ($period as $key => $value) {
            $date = $value->format('Y-m-d')  ;
            array_push($dates,$date);
        }
        return new Response($date);
        $categories = array();
        $tab = array() ;
        foreach ($objets as $object)
        {
            $date = $object->getDate()->format('Y-m-d');
            if (array_key_exists($date, $tab))
            {
                $tab[$date]++;
            }
            else
            {
                $tab[$date] = 1;
            }

            /*
            $nbr = count(array_keys($objets,$objets[0]->getDate()));
            array_push($tab,$nbr+1);
            array_push($categories, $objet->getDate()->format('Y-m-d'));*/
        }

        $series = array( array("name" => "Nombre d'objets", "data" => array_values($tab)) );
        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        // #id du div où afficher le graphe
        $ob->title->text('Nombre D\'objet Trouvés par Date');
        $ob->xAxis->title(array('text' => "Date"));
        $ob->yAxis->title(array('text' => "Nombre D'objet Trouvés"));
        $ob->xAxis->categories($dates);
        $ob->series($series);
        return $this->render('ObjetBundle:Objet:Objet.html.twig', array('chart' => $ob));

    }

    public function coinobjperdAction()
    {

        return $this->render('ObjetBundle:Objet:coinobjperd.html.twig');
    }

    public function coinobjtrouvAction()
    {

        return $this->render('ObjetBundle:Objet:coinobjtrouv.html.twig');
    }

    public function reclamerObjAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->find($id);
        if ($objet->getNature() == "Objet Perdu") {

                $inter = new Interaction();
                $inter->setIdUser($this->getUser());
                $inter->setIdObjet($objet);
                $inter->setStatut("Perdu-->Trouvé par");
                $em->persist($inter);
                $em->flush();
                return $this->redirectToRoute('affichobjperd');
        }
            else
                {
                $inter = new Interaction();
                $inter->setIdUser($this->getUser());
                $inter->setIdObjet($objet);
                $inter->setStatut("Trouvé-->propriétaire de");
                $em->persist($inter);
                $em->flush();
                return $this->redirectToRoute('affichobjtrouv',array('inter'=>$inter));

                }
    }

    public function viewetatAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $inter=$em->getRepository(Interaction::class)->findByidObjet($request->get("id"));
        if($inter)
        {
            $msg= 'Votre objet perdu a été réclamé comme trouvé par '.$inter[0]->getIdUser()->getNom();
        }
        elseif ($inter&&$inter->getIdObjet()->getNature()=='Objet Trouvé')
        {
            $msg=$inter[0]->getIdUser()->getNom().'a trouvé ton Objet';
        }
        else
        {
            $msg='Aucune Réclamation';
        }
        return $this->render('ObjetBundle:Objet:updateobj.html.twig',array('msg'=>$msg));

    }


/*
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


*/

    }
