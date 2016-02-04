<?php

namespace MailerBundle\Controller;

use MailerBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EmailController extends Controller
{
    public function indexAction()
    {
        return $this->render('MailerBundle:Email:index.html.twig');
    }
    /**
     * @Route("/")
     */
    public function sendAction()
    {
        $notification = new Notification();
        $notification->setBody('bla bla');
        $notification->setSubject('Test mail');

        $sender = $this->get('emailsender');

        try {
            for ($i = 0; $i < 9000; $i++) {
                $sender->send($notification);
            }
        } catch (Exception $ex) {
            $this->render('MailerBundle:Email:error.html.twig', [
                'message' => $ex->getMessage()
            ]);
        }

        $this->render('MailerBundle:Email:success.html.twig');
    }
}
