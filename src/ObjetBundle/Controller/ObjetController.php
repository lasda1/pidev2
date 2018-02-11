<?php

namespace ObjetBundle\Controller;

use ObjetBundle\Entity\Objet;
use ObjetBundle\Form\ObjetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ObjetController extends Controller
{
    public function ajoutobjAction(Request $request,UserInterface $username)
    {
        $objet=new Objet();
        $form=$this->createForm(ObjetType::class,$objet);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $file = $objet->getPhoto();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $path = "C:/xampp/htdocs/pidev2/web" ;
            $file->move(
                $path,
                $fileName
            );
            $username = $this->container->get('security.token_storage')->getToken()->getUser();
            $objet->setUser($username);
            $objet->setPhoto($fileName);
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
        $objet=$em->getRepository(Objet::class)->objtrouv();

        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet'=>$objet
            // ...
        ));
    }


    public function deleteobjAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $objet=$em->getRepository(Objet::class)->find($id);
        $em->remove($objet);
        $em->flush();
        return $this->redirectToRoute("affichobj");
    }

    public function updateobjAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $objet=$em->getRepository(Objet::class)->find($id);
        $Form = $this->createForm( ObjetType::class, $objet);
        $Form->handleRequest($request);
        if ($Form->isValid() && $Form->isSubmitted()){
            $em->persist($objet);
            $em->flush();

        }
        return $this->render('ObjetBundle:Objet:ajoutobj.html.twig', array('form'=>$Form->createView()
            // ...
        ));

    }

}
