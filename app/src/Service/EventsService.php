<?php

namespace App\Service;

class EventsService
{
    protected $httpClient;
    protected $event;

    public function __construct($httpClient, $event)
    {
        $this->httpClient = $httpClient;
        $this->event = $event;
    }

    public function getEvent()
    {
        $eventUrl = $this->event->getEventUrl();
        $response = $this->httpClient->get($eventUrl);
        $events = json_decode($response->getBody()->getContents(), true);

        return $this->event->formatResponse($events);
    }

}