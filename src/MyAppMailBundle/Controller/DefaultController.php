<?php

namespace MyAppMailBundle\Controller;

use ColocationBundle\Entity\Colocation;
use MyAppMailBundle\Entity\Reponse;
use MyAppMailBundle\Form\ReponseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MyAppMailBundle\Entity\Mail;
use MyAppMailBundle\Form\MailType;
use Symfony\Component\HttpFoundation\Request;
use Swift_Message;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        $mail = new Mail();
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $message = \Swift_Message::newInstance()
                ->setSubject('Accusé de réception')
                ->setFrom('dhouha.boughanmi@esprit.tn')
                ->setTo($mail->getEmail())
                ->setBody(
                    $this->renderView(
                        'MyAppMailBundle:Default:email.html.twig',
                        array('nom' => $mail->getNom(), 'prenom' => $mail->getPrenom())
                    ),
                    'text/html'
                );
            $this->get('mailer')->send($message);
            return $this->redirect($this->generateUrl('my_app_mail_accuse'));
        }
        return $this->render('MyAppMailBundle:Default:index.html.twig',
            array('form' => $form->createView()));

    }

    public function successAction()
    {
        return new Response("email envoyé avec succès, Merci de vérifier votre boite
                         mail.");
    }

    public function reponseAction($id, Request $request)
    {
        $reponse = new Reponse();
        $colocation = $this->getDoctrine()->getRepository(Colocation::class)->find($id);
        $reponse->setColocation($colocation);
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $this->getUser();
            $reponse->setSender($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($reponse);
            $em->flush();
            //Sending mail operation
            $this->sendMyEmail($reponse);
            //
            return $this->redirect($this->generateUrl('colocation_homepage'));
        }
        return $this->render('MyAppMailBundle:Default:reponse.html.twig', ['form' => $form->createView()]);
    }


    public function sendMyEmail(Reponse $reponse)
    {
        $mailer = $this->get('mailer');
        $message = (new \Swift_Message($reponse->getTitre()))
            ->setFrom("sendersender2018@gmail.com")
            ->setTo("sendersender2018@gmail.com")
            ->addCc('dhouhaboughanmi383@gmail.com', "administrator")
            ->setBody(
                $this->renderView(
                    'Emails/reponse.html.twig',
                    array('message' => $reponse->getMessage())
                ),
                'text/html'
            );
        $mailer->send($message);


    }

}



