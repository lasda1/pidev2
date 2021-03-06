<?php

namespace EventBundle\Controller;

use Doctrine\ORM\OptimisticLockException;
use EventBundle\Entity\Avis;
use EventBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Mgilet\NotificationBundle\MgiletNotificationBundle;

/**
 * Event controller.
 *
 */
class EventController extends Controller
{
    /**
     * Lists all event entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('EventBundle:Event')->createQueryBuilder('e')
            ->addORderBy('e.datedebut', 'DESC')
            ->getQuery()
            ->execute();
        $notifiableRepo = $em->getRepository('MgiletNotificationBundle:Notification')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $events, /* query NOT result */
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 6));

        return $this->render('@Event/event/index.html.twig', array(
            'events' => $events,
            'pagination' => $pagination,
            'notification'=>$notifiableRepo
        ));
    }




    /**
     * Creates a new event entity.
     *
     */
    public function newAction(Request $request)
    {
        global $kernel;
        $user = $kernel->getContainer()->get('security.token_storage')->getToken()->getUser();
        $event = new Event();

        if ($user === 'anon.') {
            return $this->redirectToRoute('e_index');
        } else {

            $form = $this->createForm('EventBundle\Form\EventType', $event);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $file = $event->getPhoto();

                $fileName = md5(uniqid('', true)).'.'.$file->guessExtension();
                $path = "C:/xampp/htdocs/pidev2/web" ;
                $file->move(
                    $path,
                    $fileName
                );
                $em = $this->getDoctrine()->getManager();
                $event->setCreatedAt(new \DateTime());
                $event->setEnable(0);
                $event->setUser($user);
                $event->setPhoto($fileName);
                $em->persist($event);
                $em->flush();

                return $this->redirectToRoute('e_show', array('id' => $event->getId()));
            }

            return $this->render('@Event/event/new.html.twig', array(
                'event' => $event,
                'form' => $form->createView(),
            ));
        }
    }

    /**
     * Finds and displays a event entity.
     *
     */
    public function showAction(Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);
        global $kernel;
        $user = $kernel->getContainer()->get('security.token_storage')->getToken()->getUser();


        $em = $this->getDoctrine()->getManager();
        $avis = $em->getRepository('EventBundle:Avis')->findOneBy(array('idevent' => $event->getId(),'iduser'=>$user));


        return $this->render('@Event/event/show.html.twig', array(
            'event' => $event,
            'avis'=>$avis,
            'delete_form' => $deleteForm->createView(),
        ));
    }



    /**
     * Displays a form to edit an existing event entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        global $kernel;
        $user = $kernel->getContainer()->get('security.token_storage')->getToken()->getUser();
        $event = new Event();
        $em = $this->getDoctrine()->getManager();

        $event = $em->getRepository('EventBundle:Event')->find($id);

        if ($user === 'anon.' or $user != $event->getUser()) {

            return $this->redirectToRoute('e_index');
        } else {
            $date=new \DateTime("now + 3 day");

            if ($event->getDatedebut()<=$date){
                $this->addFlash('danger','Vous ne pouvez pas modifier cet evennement ');
                return $this->redirectToRoute('e_show', array('id' => $event->getId()));
            }else{
            $deleteForm = $this->createDeleteForm($event);
            $editForm = $this->createForm('EventBundle\Form\EventType', $event);
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $file = $event->getPhoto();

                $fileName = md5(uniqid('', true)).'.'.$file->guessExtension();
                $path = "C:/wamp64/www/pidev2/web" ;
                $file->move(
                    $path,
                    $fileName
                );
                $event->setPhoto($fileName);
                //$event->setEnable(1);
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('e_show', array('id' => $event->getId()));
            }

            return $this->render('@Event/event/edit.html.twig', array(
                'event' => $event,
                'form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ));
        }}
    }

    /**
     * Deletes a event entity.
     *
     */
    public function deleteAction(Request $request, Event $event)
    {
        global $kernel;
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

                $em->remove($event);



                    $manager = $this->get('mgilet.notification');
                    $notif = $manager->createNotification('Event Notification !');
                    $notif->setMessage("L'évennement a été annulé.");
                    $notif->setLink('http://symfony.com/');
                    $notif->setIdEvent($event);

                        try {
                            $manager->addNotification(array($event), $notif, true);
                            $em->flush();
                        } catch (OptimisticLockException $exception) {


                    }


        }

        return $this->redirectToRoute('e_index');
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('e_delete', array('id' => $event->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    public function participerAction($id)
    {
        global $kernel;
        $user = $kernel->getContainer()->get('security.token_storage')->getToken()->getUser();
        if ($user !== 'anon.') {

                $em = $this->getDoctrine()->getManager();

            $event = $em->getRepository('EventBundle:Event')->find($id);
            if ($event->getNbMax()==0 or $user===$event->getUser()){
                $this->addFlash('success','Vous ne pouvez pas participer à votre évennement !!');
                return $this->redirectToRoute('e_show', array('id' => $id));

            }else{
            $event->setNbMax($event->getNbMax()-1);
            $em->persist($event);
            $em->flush();
                $avis = new Avis();

                $avis->setIduser($user->getId());
                $avis->setIdevent((int)$id);

                $em->persist($avis);
                $em->flush();
                return $this->redirectToRoute('e_show', array('id' => $id));
            }}
            else
                return $this->redirectToRoute('e_index');

    }

    public function annulerParticipationAction($id)
    {
        global $kernel;
        $user = $kernel->getContainer()->get('security.token_storage')->getToken()->getUser();
        if ($user !== 'anon.') {

            $em = $this->getDoctrine()->getManager();

            $event = $em->getRepository('EventBundle:Event')->find($id);
            $avis = $em->getRepository('EventBundle:Avis')->findOneBy(array('idevent'=>$id,'iduser'=>$user));
            $em->remove($avis);
            $event->setNbMax($event->getNbMax()+1);
            $em->persist($event);
                $em->flush();
            return $this->redirectToRoute('e_show', array('id' => $id));
        }
        else
            return $this->redirectToRoute('e_index');
    }


    public function ratingAction($id,$val)
    {
        global $kernel;
        $user = $kernel->getContainer()->get('security.token_storage')->getToken()->getUser();
        if ($user !== 'anon.') {
            $em = $this->getDoctrine()->getManager();
            $avis = $em->getRepository('EventBundle:Avis')->findOneBy(array('idevent' => $id,'iduser'=>$user));

            $u=$avis->getIduser();
            $em->remove($avis);
            $em->flush();

            $avis=new Avis();
            $avis->setIduser($u);
            $avis->setIdevent($id);
            $avis->setAvis($val);
            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute('e_show', array('id' => $avis->getIdevent()));
        }
        else
            return $this->redirectToRoute('e_index');

    }

    public function rechercheAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository('EventBundle:Event')->recherche($_GET['chose']);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $events, /* query NOT result */
            $request->query->getInt('page',1 ),
            $request->query->getInt('limit', 6));

        return $this->render('@Event/event/recherche.html.twig', array(
            'events' => $events,
            'pagination' => $pagination
        ));
    }

    public function listNotificationAction()
    {
        $em = $this->getDoctrine()->getManager();

        $notifiableRepo = $em->getRepository('MgiletNotificationBundle:Notification')->findAllForNotifiableId($this->getUser());

        return $this->render('@Event/event/index.html.twig',array('notification'=>$notifiableRepo));
    }




}

