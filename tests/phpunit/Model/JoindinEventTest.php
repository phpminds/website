<?php

namespace App\Tests\Model;

use App\Model\Email;
use App\Model\Event\Entity\Speaker;
use App\Model\Event\Entity\Supporter;
use App\Model\Event\Entity\Talk;
use App\Model\Event\Entity\Venue;
use App\Model\JoindinEvent;
use App\Model\Event\Event;
use App\Model\Twitter;

class JoindinEventTest extends \PHPUnit_Framework_TestCase
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
        $settings = require __DIR__ . '/../../../app/configs/settings_development.php';

        $joindin = $settings['settings']['joindin'];

        $this->joindinEvent = new JoindinEvent(
            $joindin['key'],
            $joindin['baseUrl'],
            $joindin['callback'],
            $joindin['token']
        );


        $email = new Email('phpminds.org@gmail.com');
        $twitter = new Twitter('@PHPMiNDS');

        $startDate = '2015-12-17';
        $startTime = '20:00';
        $eventDuration = 'PT2H';

        $speaker = new Speaker('A', 'Speaker', $email, $twitter);

        $talk = new Talk('A title', 'A description', $speaker, $eventDuration);

        $venue = new Venue('a little venue', 'by the sidewalk', 'Europe');

        $supporter = new Supporter('a big fan', 'youareawso.me', $twitter, $email, 'http://nolo.go');

        $this->event = new Event(
            $talk,
            $startDate,
            $startTime,
            $venue,
            $supporter
        );
    }


    public function testCanCreateAnEventPayload()
    {
        $createEventPayload = $this->joindinEvent->getCreateEventPayload($this->event, 'a name', 'a description');

//        print_r($createEventPayload);
    }
}