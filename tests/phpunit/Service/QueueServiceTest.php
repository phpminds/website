<?php

namespace App\Tests\Service;

use App\Service\QueueService;


class QueueServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QueueService
     */
    protected $service;

    protected $settings;

    public function setUp()
    {
        $this->settings = require __DIR__ . '/../../../app/configs/settings_development.php';
        $config = $this->settings['settings']['queue'];
        $connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password']
        );

        $message = new \PhpAmqpLib\Message\AMQPMessage();

        $this->service = new QueueService(
            $connection,
            $message
        );

    }

    public function testCanPublishAMessage()
    {
        $this->service->setMessageBody("A basic message");
        $this->service->publish();
    }
}