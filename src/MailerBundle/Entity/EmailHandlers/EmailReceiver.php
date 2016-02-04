<?php

namespace MailerBundle\Entity\EmailHandler;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EmailReceiver
{
    public function listen()
    {
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare(
            'rpc_queue',    #queue
            false,          #passive
            false,          #durable
            false,          #exclusive
            false           #autodelete
        );

        $channel->basic_qos(
            null,   #prefetch size
            1,      #prefetch count
            null    #global
        );

        $channel->basic_consume(
            'rpc_queue',                #queue
            '',                         #consumer tag
            false,                      #no local
            false,                      #no ack
            false,                      #exclusive
            false,                      #no wait
            array($this, 'callback')    #callback
        );

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    /**
     * Executes when a message is received.
     *
     * @param AMQPMessage $req
     */
    public function callback(AMQPMessage $req)
    {

        /*
         * Creating a reply message with the same correlation id than the incoming message
         */
        $msg = new AMQPMessage(
            array('correlation_id' => $req->get('correlation_id'))  #options
        );

        /*
         * Publishing to the same channel from the incoming message
         */
        $req->delivery_info['channel']->basic_publish(
            $msg,                   #message
            '',                     #exchange
            $req->get('reply_to')   #routing key
        );

        /*
         * Acknowledging the message
         */
        $req->delivery_info['channel']->basic_ack(
            $req->delivery_info['delivery_tag'] #delivery tag
        );
    }

    /**
     * @param \stdClass $credentials
     * @return bool
     */
    private function auth(\stdClass $credentials)
    {
        if (($credentials->username == 'admin') && ($credentials->password == 'admin')) {
            return true;
        } else {
            return false;
        }
    }
}