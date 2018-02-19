<?php

namespace ObjetBundle\Controller;

use Ob\HighchartsBundle\Highcharts\Highchart;
use ObjetBundle\Entity\Objet;
use ObjetBundle\Form\ObjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        ));
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

        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet' => $objet, 'nature' => 1
            // ...
        ));
    }

    public function affichobjperdAction()
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->objperd();
        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet' => $objet, 'nature' => 2
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
        $objets = $em->getRepository(Objet::class)->objperd();
        $tab = array();
        $categories = array();
        foreach ($objets as $objet)
        { array_push($tab, $objet->getUser()->getId());
        array_push($categories, $objet->getDate()->format('Y-m-d H:i:s')); }
        $series = array( array("name" => "Nb étudiants", "data" => $tab) );
        $ob = new Highchart();
        $ob->chart->renderTo('linechart');
        // #id du div où afficher le graphe
        $ob->title->text('Nombre des étudiants par niveau');
        $ob->xAxis->title(array('text' => "Classe"));
        $ob->yAxis->title(array('text' => "Nb étudiants"));
        $ob->xAxis->categories($categories);
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
    public function searchAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $search=$request->get('search');
            $em=$this->getDoctrine()->getManager();
            $objet=$em->getRepository(Objet::class)->recherche($search);
            //initialiser Serializer
            $se=new Serializer(array(new ObjectNormalizer()));
            //Normaliser la liste
            $data=$se->normalize($objet);
            //Codage sous forme JSON
            return new JsonResponse($data);
        }
        return $this->render('ObjetBundle:Objet:recherche.html.twig',array(

        ));
    }



    }
