<?php

namespace App\Tests\Service;

use App\Service\EventsService;

class EventsServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventsService
     */
    protected $service;

    public function setUp()
    {
        $settings = require __DIR__ . '/../../../app/configs/settings_development.php';

        $joindin = $settings['settings']['joindin'];
        $meetup = $settings['settings']['meetups'];

        $this->service = new EventsService(
            new \GuzzleHttp\Client(),
            new \App\Model\MeetupEvent($meetup['apiKey'], $meetup['baseUrl'], $meetup['api-testing']['group_urlname']),
            new \App\Model\JoindinEvent($joindin['key'], $joindin['baseUrl'], $joindin['callback'])
        );
    }

    public function testGetListOfMeetupVenues()
    {
        $venues = $this->service->getVenues();

        $this->asserttrue(!empty($venues));
    }

    public function testCanCreateANewMeetup()
    {
        // Get list of venues - pick first? - get venue_id
        // create new meetup
        // check the new meetup exists
    }
}