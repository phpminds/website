<?php

namespace PHPMinds\Tests\Model;

use PHPMinds\Model\Event\Event;


class MeetupEventTest extends \PHPMinds\Tests\Helper
{

    /**
     * @var \App\Model\MeetupEvent
     */
    protected $meetupEvent;

    /**
     * @var Event
     */
    protected $event;

    public function setUp()
    {
        $settings = $this->getSettings();

        $meetup = $settings['settings']['meetups'];

        $meetupConfig = new \PHPMinds\Config\MeetupConfig(
            [
                'apiKey'        => $meetup['apiKey'],
                'baseUrl'       => $meetup['baseUrl'],
                'groupUrlName'  => $meetup['PHPMinds']['group_urlname'],
                'publishStatus' => $meetup['publish_status']
            ]
        );

        $this->meetupEvent = new \PHPMinds\Model\MeetupEvent($meetupConfig);

        $this->event = $this->getEvent();
    }

    public function testCanCreateAnEventPayload()
    {
        $eventPayload = $this->meetupEvent->getCreateEventPayload($this->event);

        $expected = [
            'name' => 'A title',
            'description' => 'A description',
            'venue_id' => 123,
            'publish_status' => 'draft',
            'time' => $this->event->getDate()->getTimestamp() * 1000,
            'venue_visibility' => 'members'
        ];

        $this->assertEquals($expected, $eventPayload);
    }
}