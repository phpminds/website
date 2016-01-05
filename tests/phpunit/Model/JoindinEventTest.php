<?php

namespace PHPMinds\Tests\Model;


use PHPMinds\Model\JoindinEvent;
use PHPMinds\Model\Event\EventModel;


class JoindinEventTest extends \PHPMinds\Tests\Helper
{
    /**
     * @var JoindinEvent
     */
    protected $joindinEvent;

    /**
     * @var EventModel
     */
    protected $event;

    public function setUp()
    {
        $settings = $this->getSettings();

        $joindin = $settings['settings']['joindin'];

        $joindinConfig = new \PHPMinds\Config\JoindinConfig([
            'apiKey'            => $joindin['key'],
            'baseUrl'           => $joindin['baseUrl'],
            'frontendBaseUrl'   => $joindin['frontendBaseUrl'],
            'callback'          => $joindin['callback'],
            'username'          => $joindin['username']
        ]);

        $fileRepository = new \PHPMinds\Repository\FileRepository(
            $settings['settings']['file_store']['path']
        );

        $this->joindinEvent = new JoindinEvent(
            $joindinConfig, $fileRepository
        );

        $this->event = $this->getEvent();

        $this->event->setName("PHPMinds");
        $this->event->setDescription("Event description");
    }


    public function testCanCreateAnEventPayload()
    {
        $eventPayload = $this->joindinEvent->getCreateEventPayload($this->event, 'a name', 'a description');

        $expectedPayload = [
            'name' => 'PHPMinds December 2015',
            'description' => 'Event description',
            'start_date' => '2015-12-17 20:00:00',
            'end_date' => '2015-12-17 22:00:00',
            'tz_continent' => 'Europe',
            'tz_place' => 'London',
            'location' => 'a little venue'
        ];

        $this->assertSame($expectedPayload, $eventPayload);
    }
}