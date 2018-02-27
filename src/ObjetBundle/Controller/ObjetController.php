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
        , ['success' => 1]));
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
        $message = \Swift_Message::newInstance()
            ->setSubject('kadha')
            ->setFrom('espritentraide@gmail.com')
            ->setTo('bader.chtara@gmail.com')
            ->setBody('Bonjour');
        $this->get('mailer')->send($message);
        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet' => $objet, 'nature' => 1
        ));
    }

    public function affichobjperdAction()
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->objperd();
        //$interaction = $em->getRepository(Interaction::class)->findAll();
        return $this->render('ObjetBundle:Objet:affichobj.html.twig', array('objet' => $objet, 'nature' => 2
            // ...
        ));
    }


    public function deleteobjAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository(Objet::class)->find($id);
        $interaction=$em->getRepository(Interaction::class)->findByidObjet($id);
        if($interaction){
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
        //  $objets = $em->getRepository(Objet::class)->objtrouv();
        $objet_nature='Objet Perdu';
        $objet_type = array('Ordinateur', 'Telephone', 'CIN', 'Chargeur', 'Autres');
        $nbobjet = array();
        $tyobjet = array();


        foreach ($objet_type as $ot) {
            $em = $this->getDoctrine()->getManager();
            $objet = $em->getRepository(Objet::class)->nbpardate($objet_nature, $ot);
            array_push($nbobjet, intval($objet));
            array_push($tyobjet, $ot);
        }
        // return new Response($nbobjet);
        $series = array(
            array(
                'name' => 'Objet',
                'type' => 'column',
                'color' => '#4572A7',
                'yAxis' => 0,
                'data' => $nbobjet,));
        $yData = array(
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + "" }'),
                    'style' => array('color' => '#4572A7')),
                'gridLineWidth' => 0,
                'title' => array(
                    'text' => "Nombre d'objet",
                    'style' => array('color' => '#4572A7')),),);
        $ob = new Highchart();
        $ob->chart->renderTo('container');
        $ob->chart->type('column');
        $ob->title->text("Nombre d'objet par catégorie");
        $ob->xAxis->categories($tyobjet);

        $ob->yAxis($yData);
        $ob->legend->enabled(false);
        $formatter = new Expr('function (){ 
        var unit = {
	     "Objet": "Objet(s)", }
	 [this.series.name]; 
	 return this.x + ": <b>" + this.y + "</b> " + unit; }');
        $ob->tooltip->formatter($formatter);
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
                        return $this->redirectToRoute('affichobjperd', array('inter' => $inter));
                    }
                    else
                    {
                        $inter = new Interaction();
                        $inter->setIdUser($this->getUser());
                        $inter->setIdObjet($objet);
                        $inter->setStatut($this->getUser()->getNom().' a trouvé Cet Objet');
                        $em->persist($inter);
                        $em->flush();
                        return $this->redirectToRoute('affichobjtrouv', array('inter' => $inter));
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

}
