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

}