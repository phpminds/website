<?php

namespace App\Tests\Model;


use App\Model\JoindinEvent;
use App\Model\Event\Event;


class JoindinEventTest extends \App\Tests\Helper
{
    /**
     * @var JoindinEvent
     */
    protected $joindinEvent;

    /**
     * @var Event
     */
    protected $event;

    public function setUp()
    {
        $settings = $this->getSettings();

        $joindin = $settings['settings']['joindin'];

        $joindinConfig = new \App\Config\JoindinConfig([
            'apiKey'            => $joindin['key'],
            'baseUrl'           => $joindin['baseUrl'],
            'frontendBaseUrl'   => $joindin['frontendBaseUrl'],
            'callback'          => $joindin['callback'],
            'username'          => $joindin['username']
        ]);

        $fileRepository = new \App\Repository\FileRepository(
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