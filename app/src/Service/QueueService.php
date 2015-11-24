<?php

namespace App\Service;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueService
{
    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    /**
     * @var AMQPMessage
     */
    protected $msg;

    /**
     * @var string
     */
    protected $queueName;

    /**
     * @var AMQPChannel
     */
    protected $channel;

    public function __construct(AMQPStreamConnection $connection, AMQPMessage $msg, $queueName = 'generic')
    {
        $this->connection   = $connection;
        $this->msg          = $msg;
        $this->queueName    = $queueName;
    }

    public function setMessageBody($msgBody)
    {
        $this->msg->setBody($msgBody);
    }

    public function setQueueName($name)
    {
        $this->queueName = $name;
    }

    public function publish()
    {
        $this->init();
        $this->channel->basic_publish($this->msg, '', $this->queueName);
        $this->close();
    }

    public function process(callable $callback)
    {
        $this->init();
        $this->channel->basic_consume($this->queueName, '', false, true, false, false, $callback);
        while(count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    protected function init()
    {
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($this->queueName, false, false, false, false);
    }

    protected function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
