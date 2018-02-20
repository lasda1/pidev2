<?php

namespace ColocationBundle\Controller;

use ColocationBundle\Entity\Colocation;
use ColocationBundle\Form\ColocationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DefaultController extends Controller
{
    public function indexAction()
    {      $em=$this->getDoctrine()->getManager();
        $colocation=$em->getRepository(Colocation::class)->findAll();
        return $this->render('ColocationBundle:Default:index.html.twig',array("colocations"=>$colocation));

    }

    public function ajoutAction(Request $request)
    {
        $colocation=new Colocation();
        $form = $this->createForm(ColocationType::class, $colocation);
        $form->handleRequest($request);
        dump($colocation);


        if ($form->isSubmitted() && $form->isValid()) {
            $currentUser = $this->getUser();
            $colocation->setUtilisateur($currentUser);
            $em = $this->getDoctrine()->getManager();
             $photo = $colocation->getPhoto();
          $photo1 = $colocation->getPhoto1();
            $photo2 = $colocation->getPhoto2();
            $fileNameP = uniqid().'.'.$photo->guessExtension();
              $fileNameP2 = uniqid().'.'.$photo1->guessExtension();
            $fileNameP3 = uniqid().'.'.$photo2->guessExtension();

            // Move the file to the directory where brochures are stored
           $photo->move(
                $this->getParameter('photos_directory'),
                $fileNameP
            );
 $photo1->move(
                $this->getParameter('photos_directory'),
                $fileNameP2
            );
 $photo2->move(
                $this->getParameter('photos_directory'),
                $fileNameP3
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
           $colocation->setPhoto($fileNameP);
           $colocation->setPhoto1($fileNameP2);
           $colocation->setPhoto2($fileNameP3);

            // ... persist the $product variable or any other work
            $em->persist($colocation);
            $em->flush();
            return $this->redirectToRoute('colocation_homepage');
        }

        return $this->render('ColocationBundle:Default:background.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function afficherAction()
    {       $em=$this->getDoctrine()->getManager();
        $colocation=$em->getRepository(Colocation::class)->findAll();
        return $this->render('ColcationBundle:Colocation:index.html.twig',array("colocations"=>$colocation));

    }
    public function supprimerAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $colocation=$em->getRepository(Colocation::class)->find($id);
        $em->remove($colocation);
        $em->flush();
        return($this->redirectToRoute('colocation_homepage'));
    }

    public function rechercheAction(Request $request)
    {


        if($request->isMethod('Post')){


            $em=$this->getDoctrine()->getManager();
            $ville=$request->get('ville');
            $colocation=$em->getRepository(Colocation::class)->findBy(['ville' => $ville]);

            return $this->render('ColocationBundle:Default:index.html.twig',array("colocations"=>$colocation));


        }
        $em=$this->getDoctrine()->getManager();			///pour afficher une autre fois dans la page recherche
        $colocation=$em->getRepository(Colocation::class)->findAll();     ///pour afficher une autre fois dans la page recherche
        return $this->render('ColocationBundle:Default:index.html.twig',array("colocations"=>$colocation));
        ///pour afficher une autre fois dans la page recherche
    }

    public function  showAction($id)
    {
        $colocation = $this->getDoctrine()->getRepository(Colocation::class)->find($id);
        return $this->render('@Colocation/Default/show.html.twig', ['colocation' => $colocation]);
    }
    public  function  mesoffresAction(){
        $user = $this->getUser();
        $colocations = $this->getDoctrine()->getRepository(Colocation::class)->findByUtilisateur($user);
        return $this->render('@Colocation/Default/mesoffres.html.twig', ['colocations' => $colocations]);
    }


    public function  reponseAction($id)
    {
        $colocation = $this->getDoctrine()->getRepository(Colocation::class)->find($id);
        dump($colocation);die;
        return $this->render('@Colocation/Default/show.html.twig', ['colocation' => $colocation]);
    }





}
