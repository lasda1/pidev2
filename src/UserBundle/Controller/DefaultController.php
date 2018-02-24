<?php

namespace UserBundle\Controller;

use CoVoiturageBundle\Entity\CoVoiturage;
use CoVoiturageBundle\Entity\CoVoiturageDays;
use CoVoiturageBundle\Entity\CoVoiturageRequests;
use EspaceEtudeBundle\Entity\Section;
use EspaceEtudeBundle\Enum\Niveau;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EspaceEtudeBundle\Form\SectionType;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->redirectToRoute("afficher_section");
    }
}
