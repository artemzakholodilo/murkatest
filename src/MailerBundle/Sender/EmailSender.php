<?php

namespace MailerBundle\Sender;

use MailerBundle\Entity\Notification;

class EmailSender extends AbstractSender
{
    protected $transport;

    private $userName = 'azaholodilo@gmail.com';

    /**
     * EmailSender constructor.
     * @param \Swift_Mailer $mailer
     * @param \Swift_SmtpTransport $smtpTransport
     */
    public function __construct(\Swift_Mailer $mailer, \Swift_SmtpTransport $smtpTransport)
    {
        $transport = $smtpTransport::newInstance('gmail')
                     ->setUsername($this->userName)
                     ->setPassword('db1708j30a2');

        $this->transport = $mailer::newInstance($transport);
    }

    /**
     * @param Notification $notification
     * @return null
     */
    public function send(Notification $notification)
    {
        $message = \Swift_Message::newInstance($notification->getSubject())
                    ->setFrom([$this->userName => 'Sender'])
                    ->setTo([$notification->getSubject()])
                    ->setBody($notification->getBody());

        $this->transport->send($message);
    }
}