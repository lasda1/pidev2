<?php

namespace ObjetBundle\Controller;

use ObjetBundle\Entity\Objet;
use ObjetBundle\Form\ObjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ObjetController extends Controller
{
    public function ajoutobjAction(Request $request)
    {
        $objet=new Objet();
        $form=$this->createForm(ObjetType::class,$objet);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $objet = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($objet);
            $em->flush();
        }


        return $this->render('ObjetBundle:Objet:ajoutobj.html.twig', array('form'=>$form->createView()
            // ...
        ));
    }

    public function affichobjAction()
    {
        $em =$this->getDoctrine()->getManager();
        $objet=$em->getRepository(Objet::class)->findAll();

        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet'=>$objet
            // ...
        ));
    }

    public function deleteobjAction()
    {
        return $this->render('ObjetBundle:Objet:deleteobj.html.twig', array(
            // ...
        ));
    }

    public function updateobjAction()
    {
        return $this->render('ObjetBundle:Objet:updateobj.html.twig', array(
            // ...
        ));
    }

}
