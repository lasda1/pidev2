<?php

namespace EventBundle\Controller;

use Mgilet\NotificationBundle\NotifiableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventBundle:Default:index.html.twig');
    }


    /**
     * @Route("/send-notification", name="send_notification")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function sendNotification(Request $request)
    {
        $manager = $this->get('mgilet.notification');
        $notif = $manager->createNotification('Hello world !');
        $notif->setMessage('This a notification.');
        $notif->setLink('http://symfony.com/');
        // or the one-line method :
        // $manager->createNotification('Notification subject','Some random text','http://google.fr');

        // you can add a notification to a list of entities
        // the third parameter ``$flush`` allows you to directly flush the entities
        $manager->addNotification(array($this->getUser()), $notif, true);

        return $this->redirectToRoute('e_index');
    }


    /**
     * @param NotifiableInterface $notifiable
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{notifiable}",name="notification_list")
     * @Method("GET")
     */

    public function listAction($notifiable)
    {
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:Notification');
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers, $encoders);
        $notifications = array_filter($notifiableRepo->findAll(), function ($n, $k) {
            $userId = $this->getUser()->getId();
            return ($n->getIdEvent()->getUser()->getId() == $userId);
        }, ARRAY_FILTER_USE_BOTH);
        $result = array();
        foreach ($notifications as $n) {
            array_push($result,
                array("message" => $n->getMessage())
            );
        }
        $notifs = $serializer->serialize($result, 'json');
        return new JsonResponse($result);

        /* return $this->render('MgiletNotificationBundle::notifications.html.twig', array(
             'notifiableNotifications' => $notifiableRepo->findAll()
         ));*/
    }


}









