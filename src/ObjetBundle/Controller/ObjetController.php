<?php

namespace ObjetBundle\Controller;

use DateInterval;
use DatePeriod;
use DateTime;
use Ob\HighchartsBundle\Highcharts\Highchart;
use ObjetBundle\Entity\Interaction;
use ObjetBundle\Entity\Objet;
use ObjetBundle\Entity\traitafter;
use ObjetBundle\Form\ObjetType;
use ObjetBundle\Repository\ObjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\ExpressionLanguage\Tests\Node\Obj;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Date;
use Zend\Json\Expr;
use Swift_Message;

class ObjetController extends Controller
{
    public function ajoutobjtrouvAction(Request $request, UserInterface $username)
    {
        $objet = new Objet();
        $form = $this->createForm(ObjetType::class, $objet);
        $form->handleRequest($request);
        $msg="Ajouter Un Nouveau Objet Trouvé";
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
            return $this->redirectToRoute("affichobjtrouv",array(['success' => 1]));
        }


        return $this->render('ObjetBundle:Objet:ajoutobj.html.twig', array('form' => $form->createView()
            // ...
        ,'msg'=>$msg));
    }

    public function ajoutobjperdAction(Request $request, UserInterface $username)
    {
        $objet = new Objet();
        $form = $this->createForm(ObjetType::class, $objet);
        $form->handleRequest($request);
        $msg="Ajouter Un Nouveau Objet Perdu";
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


        return $this->render('ObjetBundle:Objet:ajoutobj.html.twig', array('form' => $form->createView(),'msg'=>$msg
            // ...
        ));
    }

    public function affichobjtrouvAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->objtrouv();
        $ob=$em->getRepository(Objet::class)->objperd();
        $x=array();
        foreach ($objet as $o)
        {
            foreach ($ob as $operd)
            {
                if( $o->getUser()->getId()!=$operd->getUser()->getId()&&$o->getType()==$operd->getType()&&$o->getLieu()==$operd->getLieu())
                {
                    array_push($x,$operd);
                }
            }

        }

        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet' => $objet, 'nature' => 1,'x'=>$x,
        ));
    }

    public function affichobjperdAction()
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->objperd();
        $ob=$em->getRepository(Objet::class)->objtrouv();
        $x=array();
        foreach ($objet as $o)
        {
            foreach ($ob as $otrouv)
            {
                if( $o->getUser()->getId()!=$otrouv->getUser()->getId()&&$o->getType()==$otrouv->getType()&&$o->getLieu()==$otrouv->getLieu())
                {
                array_push($x,$otrouv);
                }

            }

        }

        //$interaction = $em->getRepos1itory(Interaction::class)->findAll();
        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet' => $objet, 'nature' => 2,'x'=>$x
            // ...
        ));
    }


    public function deleteobjAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->find($id);
        $interaction=$em->getRepository(Interaction::class)->findByidObjet($id);
        if($interaction)
        {
            $em->remove($interaction[0]);
        }
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

        if ($objet->getNature()=='Objet Perdu')
        {$msg='Ajouter Un Nouveau Objet Perdu';}
        else
        {$msg='Ajouter Un Nouveau Objet Trouvé';}
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
            $objet->setEnable(0);
            $objet->setPhoto($fileName);
            $em->persist($objet);
            $em->flush();
            if ($objet->getNature() == 'Objet Perdu')
            {
                return $this->redirectToRoute("affichobjperd");

            }
            else
            {
                return $this->redirectToRoute("affichobjtrouv");

            }
        }
        return $this->render('ObjetBundle:Objet:ajoutobj.html.twig', array('form' => $Form->createView(),'msg'=>$msg            // ...
        ));

    }

    public function coinobjperdAction()
    {

        /*$em=$this->getDoctrine()->getManager();
        $objet=$em->getRepository(Objet::class)->nbobjperd();*/

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
        $inter = $this->getDoctrine()->getRepository(Interaction::class)->findtrouver($id);
        if($inter == null)
        {
                    if ($objet->getNature() == "Objet Perdu") {

                        $inter = new Interaction();
                        $inter->setIdUser($this->getUser());
                        $inter->setIdObjet($objet);
                        $inter->setStatut('Cet objet perdu a été réclamé comme trouvé par ' . $this->getUser()->getNom());
                        $em->persist($inter);
                        $em->flush();
                        return $this->redirectToRoute('showsingle',array('id'=>$id));
                    }
                    else
                    {
                        $inter = new Interaction();
                        $inter->setIdUser($this->getUser());
                        $inter->setIdObjet($objet);
                        $inter->setStatut($this->getUser()->getNom().' a trouvé Cet Objet');
                        $em->persist($inter);
                        $em->flush();
                        return $this->redirectToRoute('showsingle',array('id'=>$id));
                    }
        }
        else
        {
            $msg='Déja Réclamé';
            return $this->redirectToRoute('affichobjtrouv',array('msg'=>$msg));

        }
    }

    public function showsingleAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->find($id);
        $inter=$em->getRepository(Interaction::class)->findOneBy(array('idObjet'=>$objet));
        $interaction = $em->getRepository(Interaction::class)->findOneBy(
            array('idUser'=>$this->getUser(),'idObjet'=>$objet));
       // $msg=$interaction->getStatut();
        if($interaction) {

            return $this->render('ObjetBundle:Objet:showsingle.html.twig', array('objet' => $objet, 'interaction' => $interaction,'inter'=>$inter));
        }
        else{

            return $this->render('ObjetBundle:Objet:showsingle.html.twig', array('objet' => $objet, 'interaction' => null,'inter'=>$inter ));

        }
    }

    public function mailAction( \Swift_Mailer $mailer){
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('espritentraide@gmail.com')
            ->setTo('bader.chtara@gmail.com')
            ->setBody('salut');
        $mailer->send($message);

    }

    public function supprimerInteractionAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $interaction = $em->getRepository(Interaction::class)->findByidObjet($id);
        $em->remove($interaction[0]);
        $em->flush();
        return $this->redirectToRoute('showsingle',array('id'=>$id));

    }

    public function signalerReclamationAction($id)

    {
        $em = $this->getDoctrine()->getManager();
        $interaction = $em->getRepository(Interaction::class)->findByidObjet($id);
        $sig = $this->getDoctrine()->getRepository(traitafter::class)->findtrouver($id);
        if($sig==null)
        {
            foreach ($interaction as $i){


            $signal=new traitafter();
            $signal->setUser($this->getUser());
            if($interaction!=null ){
            $signal->setInteraction($i);}
            else{
                return new Response("Pas dobjet");
            }
            $signal->setStatut('Cet Signal a été réclamé par ' . $this->getUser()->getNom());
            $em->persist($signal);
            $em->flush();
        }
        }
        return $this->redirectToRoute('showsingle',array('id'=>$id));
    }




}
