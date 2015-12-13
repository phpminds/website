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


        $email = new Email('phpminds.org@gmail.com');
        $twitter = new Twitter('@PHPMiNDS');

        $startDate = "17/12/2015";
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