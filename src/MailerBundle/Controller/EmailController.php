<?php

namespace MailerBundle\Controller;

use MailerBundle\Entity\Notification;
use MailerBundle\Sender\EmailSender;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{
    /**
     * @var EmailSender
     */
    private $sender;

    /**
     * EmailController constructor.
     * @param EmailSender $sender
     */
    public function __construct(EmailSender $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('MailerBundle:Email:index.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendAction(Request $request)
    {
        $notification = new Notification();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $notification->setBody($request->request->get('subject'), $user->getUsername());
        $notification->setSubject($request->request->get('body'));

        try {
            $this->sender->send($notification);
        } catch (\Exception $ex) {
            $this->render('MailerBundle:Email:error.html.twig', [
                'message' => $ex->getMessage()
            ]);
        }

        $this->render('MailerBundle:Email:success.html.twig');
    }
}
