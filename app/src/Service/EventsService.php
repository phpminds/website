<?php

namespace App\Service;

class EventsService
{
    /**
     * @var \GuzzleHttp\Client()
     */
    protected $httpClient;
    /**
     * @var \App\Model\MeetupEvent
     */
    protected $meetupEvent;
    /**
     * @var \App\Model\JoindinEvent
     */
    protected $joindinEvent;

    public function __construct($httpClient, $meetupEvent, $joindinEvent)
    {
        $this->httpClient = $httpClient;
        $this->meetupEvent = $meetupEvent;
        $this->joindinEvent = $joindinEvent;
    }

    public function getEvent()
    {
        $eventUrl = $this->meetupEvent->getEventUrl();
        $response = $this->httpClient->get($eventUrl);
        $events = json_decode($response->getBody()->getContents(), true);

        return $this->meetupEvent->formatResponse($events);
    }

    public function getVenues()
    {
        $venuesUrl = $this->meetupEvent->getVenuesUrl();
        return $this->httpClient->get($venuesUrl)->getBody()->getContents();
    }

    public function createMeetup($talk)
    {


    }

    public function addToJoindIn($event)
    {

    }

    public function joindinAuth()
    {



    }

    public function createJoindinEvent()
    {
        // create event
        //{"name":"Test 1","description":"Description for test 1. An awesome event with an awesome speaker. The event will take place in Nottingham, UK","start_date":"2015-12-17T12:19:00+00:00","end_date":"2015-12-17T12:20:00+00:00","tz_continent":"Europe","tz_place":"London", "location":"Nottingham"}

        // create talk
        // {"talk_title":"The first ever talk which works.","talk_description":"the first description","language":"English - UK","talk_type":"Talk","start_date":"2015-12-17T12:19:00+00:00","speakers":["Antonios Pavlakis"]}
        $this->httpClient->post(
            'http://api.dev.joind.in/v2.1/events/', [
            'json' => [
                'name' => 'Test 1',
                'description' => 'Description for test 1. An awesome event with an awesome speaker. The event will take place in Nottingham, UK',
                'start_date' => '2015-12-17T12:19:00+00:00',
                'end_date' => '2015-12-17T12:20:00+00:00',
                'tz_continent' => 'Europe',
                'tz_place' => 'Nottingham'

            ],
            'headers' => [
                'Authorization' => 'Bearer bdc3ceba53ea35ea'
            ]
        ]);


    }


}