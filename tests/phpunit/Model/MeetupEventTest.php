<?php

namespace App\Tests\Model;


use App\Model\Email;
use App\Model\Event\Entity\Speaker;
use App\Model\Event\Entity\Supporter;
use App\Model\Event\Entity\Talk;
use App\Model\Event\Entity\Venue;
use App\Model\Event\Event;
use App\Model\Twitter;

class MeetupEventTest extends \App\Tests\Helper
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

        $meetupConfig = new \App\Config\MeetupConfig(
            [
                'apiKey'        => $meetup['apiKey'],
                'baseUrl'       => $meetup['baseUrl'],
                'groupUrlName'  => $meetup['PHPMinds']['group_urlname'],
                'publishStatus' => $meetup['publish_status']
            ]
        );

        $this->meetupEvent = new \App\Model\MeetupEvent($meetupConfig);

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