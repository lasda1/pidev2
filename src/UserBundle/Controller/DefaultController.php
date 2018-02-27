<?php

namespace UserBundle\Controller;

use CoVoiturageBundle\Entity\CoVoiturage;
use CoVoiturageBundle\Entity\CoVoiturageDays;
use CoVoiturageBundle\Entity\CoVoiturageRequests;
use EspaceEtudeBundle\Entity\Matiere;
use EspaceEtudeBundle\Entity\Section;
use EspaceEtudeBundle\Enum\Niveau;
use EspaceEtudeBundle\Form\MatiereType;
use EventBundle\Entity\Event;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use EspaceEtudeBundle\Form\SectionType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Zend\Json\Expr;

class DefaultController extends Controller
{
    public function indexAction()
    {
        
        $user = $this->getUser();
        if ($user) {
            return $this->render('@User/layout.html.twig', ['user' => $user]);
        }
        return $this->redirectToRoute('fos_user_security_login');
    }

    public function newsAction(){
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->getLastThree();
        return $this->render('UserBundle::news.html.twig', ['cov' => $co]);
    }

    public function adminAction(){
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('fos_user_security_logout');
             }
        return $this->render('UserBundle::admin.html.twig');
    }

    public function CoVoiturageViewOffreParamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->findAll();
        $cor = $em->getRepository(CoVoiturageRequests::class)->getOrderedBy();
        return $this->render('UserBundle:CoVoiturage:viewoffre.html.twig', ['cov' => $co, 'cor' => $cor ,'success' => $request->get('success')]);
    }

    public function CoVoiturageViewDemandeParamAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->findAll();
        $cor = $em->getRepository(CoVoiturageRequests::class)->getOrderedBy();
        return $this->render('UserBundle:CoVoiturage:viewdemande.html.twig', ['cov' => $co, 'cor' => $cor ,'success' => $request->get('success')]);
    }

    public function CoVoiturageOffreAction(){
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->findAll();
        $cor = $em->getRepository(CoVoiturageRequests::class)->getOrderedBy();


        return $this->render('UserBundle:CoVoiturage:viewoffre.html.twig', ['cov' => $co, 'cor' => $cor]);

        //return $this->render('UserBundle::viewoffre.html.twig');
    }

    public function CoVoiturageDemandeAction(){
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->findAll();
        $cor = $em->getRepository(CoVoiturageRequests::class)->getOrderedBy();


        return $this->render('UserBundle:CoVoiturage:viewdemande.html.twig', ['cov' => $co, 'cor' => $cor]);

        //return $this->render('UserBundle::viewoffre.html.twig');
    }

    public function CoVoiturageInfoOffreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        if ($cod) {
            $cod = $cod[0];
        } else {
            $cod = null;
        }



        return $this->render('UserBundle:CoVoiturage:infooffre.html.twig', ['co' => $co, 'cod' => $cod]);
    }

    public function CoVoiturageInfoDemandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));
        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        if ($cod) {
            $cod = $cod[0];
        } else {
            $cod = null;
        }



        return $this->render('UserBundle:CoVoiturage:infodemande.html.twig', ['co' => $co, 'cod' => $cod]);
    }

    public function CoVoiturageDeleteOffreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));

        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByidc($request->get("id"));
        if ($cod) {
            $em->remove($cod[0]);
        }
        if ($cor){
            $em->remove($cor);
        }

        $em->remove($co);
        $em->flush();
        return $this->redirectToRoute('admincovoffresparam', ['success' => 3]);
    }

    public function CoVoiturageDeleteDemandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $co = $em->getRepository(CoVoiturage::class)->find($request->get("id"));

        $cod = $em->getRepository(CoVoiturageDays::class)->findByidc($request->get("id"));
        $cor = $em->getRepository(CoVoiturageRequests::class)->findByidc($request->get("id"));
        if ($cod) {
            $em->remove($cod[0]);
        }
        if ($cor){
            $em->remove($cor);
        }

        $em->remove($co);
        $em->flush();
        return $this->redirectToRoute('admincovdemandesparam', ['success' => 3]);
    }

    public function CoVoiturageDeleteOffreRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get('id'));
        $em->remove($cor);
        $em->flush();
        return $this->redirectToRoute('admincovoffresparam', ['success' => 3]);
    }

    public function CoVoiturageDeleteDemandeRequestAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $cor = $em->getRepository(CoVoiturageRequests::class)->find($request->get('id'));
        $em->remove($cor);
        $em->flush();
        return $this->redirectToRoute('admincovdemandesparam', ['success' => 3]);
    }

    public function sectionAction(Request $request){
        $user = $this->getUser();
        if ($user) {
            $sections=new Section();
            $em=$this->getDoctrine()->getManager();
            $form = $this->createForm(SectionType::class, $sections);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $s=$form->getData();
                $em->persist($s);
                $em->flush();
            }
            $em=$this->getDoctrine()->getManager();
            $niveau=new Niveau();
            $niveau=$niveau->getAvailableTypes();
            $section=$em->getRepository(Section::class)->findAll();

            return $this->render('@User/EspaceEtude/section.html.twig',array('niveaux'=>$niveau,'sections'=>$section,'form'=>$form->createView(),
            ));
        }
        return $this->redirectToRoute('fos_user_security_login');
    }
    public function deleteAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $sec=$em->getRepository(Section::class)->find($request->attributes->get('id'));
        $em->remove($sec);
        $em->flush();
        return $this->redirectToRoute("afficher_section_admin");
    }
    public function matiereAction(Request $request){
        $user = $this->getUser();
        if ($user) {
            $newMatiere=new Matiere();
            $form = $this->createForm(MatiereType::class, $newMatiere);
            $form->handleRequest($request);
            $em=$this->getDoctrine()->getManager();
            $section=$em->getRepository(Section::class)->findOneBy(array('id' =>$request->attributes->get('id')));
            if ($form->isSubmitted() && $form->isValid()) {
                $newMatiere=$form->getData();
                $em->persist($newMatiere);
                $em->flush();
                $section->addMatiere($newMatiere);
                $em->persist($section);
                $em->flush();
            }
            $matiere=$section->getMatiere();
            return $this->render('@User/EspaceEtude/matiere.html.twig',array('matiere'=>$matiere,'section'=>$section,'form'=>$form->createView(),
            ));
        }
        return $this->redirectToRoute('fos_user_security_login');

    }
    public function deleteMatiereAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $mat=$em->getRepository(Matiere::class)->find($request->attributes->get('id'));
        $sec=$em->getRepository(Section::class)->find($request->attributes->get('ids'));
        $sec=$sec->removeMatiere($mat);
       /* $em->persist($sec);
        $em->flush();*/
        $em->remove($mat);
        $em->flush();
        return $this->redirectToRoute("afficher_matiere_admin",array('id'=>$request->attributes->get('ids')));

    }
    public function updateMatiereAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $id = $request->get("id");
            $champ = $request->get("champs");
            $val = $request->get("val");
            $em = $this->getDoctrine()->getManager();
            if ($champ == "libelle") {
                $matiere = $em->getRepository(Matiere::class)->find($id);
                $matiere = $matiere->setLibelle($val);
                $em->persist($matiere);
                $em->flush();
            } elseif ($champ == "coefficient") {
                $matiere = $em->getRepository(Matiere::class)->find($id);
                $matiere = $matiere->setCoefficient($val);
                $em->persist($matiere);
                $em->flush();
            } else {
                $matiere = $em->getRepository(Matiere::class)->find($id);
                $matiere = $matiere->setType($val);
                $em->persist($matiere);
                $em->flush();
            }
            return new JsonResponse("success");
        }
    }


    public function showAdminAction(Event $event){

        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('EventBundle:Event')->find($event);
        return $this->render('@User/event/showAdmin.html.twig', array(
            'event' => $event
        ));
    }

    public function indexAdminAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $value=0;
            $events = $em->getRepository('EventBundle:Event')->createQueryBuilder('e')
                ->where('e.enable LIKE :valeur')
                ->addORderBy('e.datedebut', 'DESC')
                ->setParameter('valeur', '%'.$value.'%')
                ->getQuery()
                ->execute();
            return $this->render('@User/event/indexAdmin.html.twig', array(
                'events' => $events
            ));
        }

        return $this->redirectToRoute('admin_index');

    }

    public function indexAdminApprouverAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $em = $this->getDoctrine()->getManager();
            $value=1;
            $events = $em->getRepository('EventBundle:Event')->createQueryBuilder('e')
                ->where('e.enable LIKE :valeur')
                ->addORderBy('e.datedebut', 'DESC')
                ->setParameter('valeur', '%'.$value.'%')
                ->getQuery()
                ->execute();

            return $this->render('@User/event/indexAdminNonApprouver.html.twig', array(
                'events' => $events
            ));
        }

        return $this->redirectToRoute('admin_index');

    }

    public function approuverAction($id){
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('EventBundle:Event')->find($id);
        $event->setEnable(1);
        $em->persist($event);
        $em->flush();
        return $this->redirectToRoute('admin_index');
    }
////////////Controller Admin Objet
    public function indexAdminObjetAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $value=1;
            $objets = $em->getRepository('ObjetBundle:Objet')->createQueryBuilder('e')
                ->where('e.enable LIKE :valeur')
                ->addORderBy('e.date', 'DESC')
                ->setParameter('valeur', '%'.$value.'%')
                ->getQuery()
                ->execute();
            return $this->render('@User/objet/objetAdmin.html.twig', array(
                'objet' => $objets
            ));
        }

        return $this->redirectToRoute('admin_index');

    }

    public function indexAdminObjetApprouverAction(Request $request)
    {
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {

            $em = $this->getDoctrine()->getManager();
            $value=0;
            $objets = $em->getRepository('ObjetBundle:Objet')->createQueryBuilder('e')
                ->where('e.enable LIKE :valeur')
                ->addORderBy('e.date', 'DESC')
                ->setParameter('valeur', '%'.$value.'%')
                ->getQuery()
                ->execute();

            return $this->render('@User/objet/indexAdminObjetNonApprouver.html.twig', array(
                'objet' => $objets
            ));
        }

        return $this->redirectToRoute('admin_index');

    }

    public function approuverObjetAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository('ObjetBundle:Objet')->find($id);
        $objet->setEnable(1);
        $em->persist($objet);
        $em->flush();
        return $this->redirectToRoute('admin_objet_non_approuve');
    }

    public function admindeleteobjAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $objet = $em->getRepository('ObjetBundle:Objet')->find($id);
        $interaction=$em->getRepository('ObjetBundle:Interaction')->findByidObjet($id);
        if($interaction){
            $em->remove($interaction[0]);
        }
        $em->remove($objet);
        $em->flush();
        return $this->redirectToRoute("admin_objet_non_approuve");

    }







    public function adminStatObjPerdAction()
    {
        //  $objets = $em->getRepository(Objet::class)->objtrouv();
        $objet_nature='Objet Perdu';
        $objet_type = array('Ordinateur', 'Telephone', 'CIN', 'Chargeur', 'Autres');
        $nbobjet = array();
        $tyobjet = array();


        foreach ($objet_type as $ot) {
            $em = $this->getDoctrine()->getManager();
            $objet = $em->getRepository('ObjetBundle:Objet')->nbpardate($objet_nature, $ot);
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
        $ob->title->text("Nombre d'Objets Perdus Désapprouvés");
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
        return $this->render('@User/objet/AdminStatObjetPerdu.html.twig', array('chart' => $ob));
    }















    public function adminStatObjTrouvAction()
    {
        //  $objets = $em->getRepository(Objet::class)->objtrouv();
        $objet_nature='Objet Trouvé';
        $objet_type = array('Ordinateur', 'Telephone', 'CIN', 'Chargeur', 'Autres');
        $nbobjet = array();
        $tyobjet = array();


        foreach ($objet_type as $ot) {
            $em = $this->getDoctrine()->getManager();
            $objet = $em->getRepository('ObjetBundle:Objet')->nbpardate($objet_nature, $ot);
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
        $ob->title->text("Nombre d'Objets Trouvés Désapprouvés");
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
        return $this->render('@User/objet/AdminStatObjetTrouv.html.twig', array('chart' => $ob));
    }

}
/////////// END controller ADmin Objet


