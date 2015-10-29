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

    public function addToJoindIn($event)
    {

    }

    public function joindinAuth()
    {



    }

    public function createJoindinEvent()
    {

        $this->httpClient->post(
            'http://localhost:9000/', [
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