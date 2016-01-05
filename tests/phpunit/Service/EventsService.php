<?php

namespace PHPMinds\Tests\Service;

use PHPMinds\Service\EventsService;
use PHPMinds\Model\Email;
use PHPMinds\Model\Event\Entity\Speaker;
use PHPMinds\Model\Event\Entity\Supporter;
use PHPMinds\Model\Event\Entity\Talk;
use PHPMinds\Model\Event\Entity\Venue;
use PHPMinds\Model\JoindinEvent;
use PHPMinds\Model\Event\EventModel;
use PHPMinds\Model\Twitter;

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
            new \PHPMinds\Model\MeetupEvent($meetup['apiKey'], $meetup['baseUrl'], $meetup['publish_status'], $meetup['PHPMinds']['group_urlname']),
            new \PHPMinds\Model\JoindinEvent($joindin['key'], $joindin['baseUrl'], $joindin['frontendBaseUrl'], $joindin['callback'], $joindin['token']),
            new \PHPMinds\Repository\EventsRepository(
                new \PHPMinds\Model\Db (
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

    public function testCanCreateAJoindinEvent()
    {
        $eventDefaults = $this->settings['settings']['events'];

        $event = $this->getEvent();
        $this->service->createEvent($event);

        $this->service->getMeetupEvent()->setEventLocation('https://api.meetup.com/2/event/226158970/');

        $this->service->createJoindinEvent($eventDefaults['title'], $eventDefaults['description']);
        $this->service->createJoindinTalk();

        $eventEntity = $this->service->updateEvents();
    }

    protected function getEvent()
    {
        $email = new Email('phpminds.org@gmail.com');
        $twitter = new Twitter('@PHPMiNDS');

        $startDate = '2017-03-10';
        $startTime = '20:00';
        $eventDuration = 'PT2H';

        $speaker = new Speaker('AnAwesome', 'Speaker', $email, $twitter);
        $speaker->setId(300);

        $talk = new Talk('A title', 'A description. But I think we need a much longer description in order for joind.in to accept this talk...', $speaker, $eventDuration);
        $talk->setId(200);

        $venue = new Venue('JH', 'by the sidewalk', 'Europe');
        $venue->setId(100);

        $supporter = new Supporter('a big fan', 'youareawso.me', $twitter, $email, 'http://nolo.go');
        $supporter->setId(350);

        return new EventModel(
            $talk,
            $startDate,
            $startTime,
            $venue,
            $supporter
        );
    }
}