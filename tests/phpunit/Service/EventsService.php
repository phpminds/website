<?php

namespace App\Tests\Service;

use App\Service\EventsService;
use App\Model\Email;
use App\Model\Event\Entity\Speaker;
use App\Model\Event\Entity\Supporter;
use App\Model\Event\Entity\Talk;
use App\Model\Event\Entity\Venue;
use App\Model\JoindinEvent;
use App\Model\Event\Event;
use App\Model\Twitter;

class EventsServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventsService
     */
    protected $service;

    protected $settings;

    public function setUp()
    {
        $this->settings = require __DIR__ . '/../../../app/configs/settings_development.php';

        $joindin = $this->settings['settings']['joindin'];
        $meetup = $this->settings['settings']['meetups'];

        $db = $this->settings['settings']['db'];

        $this->service = new EventsService(
            new \GuzzleHttp\Client(),
            new \App\Model\MeetupEvent($meetup['apiKey'], $meetup['baseUrl'], $meetup['publish_status'], $meetup['PHPMinds']['group_urlname']),
            new \App\Model\JoindinEvent($joindin['key'], $joindin['baseUrl'], $joindin['callback'], $joindin['token']),
            new \App\Repository\EventsRepository(
                new \App\Model\Db (
                'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'], $db['username'], $db['password']
                )
            )
        );
    }

    public function _testGetListOfMeetupVenues()
    {
        $venues = $this->service->getVenues();

        $this->asserttrue(!empty($venues));
    }

    public function _testCanCreateACompleteEvent()
    {
        $eventDefaults = $this->settings['settings']['events'];
        $event = $this->getEvent();

        $this->service->createEvent($event);
        $this->service->createMeetup();

        $this->service->createJoindinEvent($eventDefaults['title'], $eventDefaults['description']);
        $this->service->createJoindinTalk();
    }

    public function _testCanCreateANewMeetup()
    {
        // Get list of venues - pick first? - get venue_id
        // create new meetup
        // check the new meetup exists

    }

    public function _testCanCreateAJoindinEvent()
    {
        $eventDefaults = $this->settings['settings']['events'];

        $event = $this->getEvent();
        $this->service->createEvent($event);


        $this->service->createJoindinEvent($eventDefaults['title'], $eventDefaults['description']);
        $this->service->createJoindinTalk();
    }

    protected function getEvent()
    {
        $email = new Email('phpminds.org@gmail.com');
        $twitter = new Twitter('@PHPMiNDS');

        $startDate = '2017-01-24';
        $startTime = '20:00';
        $eventDuration = 'PT2H';

        $speaker = new Speaker('AnAwesome', 'Speaker', $email, $twitter);

        $talk = new Talk('A title', 'A description. But I think we need a much longer description in order for joind.in to accept this talk...', $speaker, $eventDuration);

        $venue = new Venue('a little venue', 'by the sidewalk', 'Europe');

        $supporter = new Supporter('a big fan', 'youareawso.me', $twitter, $email, 'http://nolo.go');

        return new Event(
            $talk,
            $startDate,
            $startTime,
            $venue,
            $supporter
        );
    }
}