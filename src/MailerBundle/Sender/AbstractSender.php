<?php

namespace MailerBundle\Sender;

use MailerBundle\Entity\Notification;

abstract class AbstractSender
{
    protected $transport;

    public static function initial($sender)
    {
        return new $sender;
    }

    abstract public function send(Notification $notification);
}