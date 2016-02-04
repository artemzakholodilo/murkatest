<?php

namespace MailerBundle\Entity\EmailHandler;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EmailSender
{
    private $response;

    private $corrId;

    public function execute($credentials)
    {
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        /*
         * creates an anonymous exclusive callback queue
         * $callback_queue has a value like amq.gen-_U0kJVm8helFzQk9P0z9gg
         */
        list($callback_queue, ,) = $channel->queue_declare(
            "", 	#queue
            false, 	#passive
            false, 	#durable
            true, 	#exclusive
            false	#auto delete
        );

        $channel->basic_consume(
            $callback_queue, 			#queue
            '', 						#consumer tag
            false, 						#no local
            false, 						#no ack
            false, 						#exclusive
            false, 						#no wait
            array($this, 'onResponse')	#callback
        );

        $this->response = null;

        /*
         * $this->corr_id has a value like 53e26b393313a
         */
        $this->corrId = uniqid();
        $jsonCredentials = json_encode($credentials);

        /*
         * create a message with two properties: reply_to, which is set to the
         * callback queue and correlation_id, which is set to a unique value for
         * every request
         */
        $msg = new AMQPMessage(
            $jsonCredentials,    #body
            array('correlation_id' => $this->corrId, 'reply_to' => $callback_queue)    #properties
        );

        /*
         * The request is sent to an rpc_queue queue.
         */
        $channel->basic_publish(
            $msg,		#message
            '', 		#exchange
            'rpc_queue'	#routing key
        );

        while(!$this->response) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();

        return $this->response;
    }

    public function onResponse(AMQPMessage $rep)
    {
        if($rep->get('correlation_id') == $this->corrId) {
            $this->response = $rep->body;
        }
    }
}