<?php

namespace App\Service;

use App\Model\Event\Event;
use App\Model\Event\Entity\Venue;
use App\Repository\EventsRepository;
use App\Model\MeetupEvent;


class EventsService
{
    /**
     * @var \GuzzleHttp\Client()
     */
    protected $httpClient;

    /**
     * @var MeetupEvent
     */
    protected $meetupEvent;

    /**
     * @var \App\Model\JoindinEvent
     */
    protected $joindinEvent;

    /**
     * @var EventsRepository
     */
    protected $eventsRepository;

    public function __construct($httpClient, $meetupEvent, $joindinEvent, EventsRepository $eventsRepository)
    {
        $this->httpClient = $httpClient;
        $this->meetupEvent = $meetupEvent;
        $this->joindinEvent = $joindinEvent;
        $this->eventsRepository = $eventsRepository;
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
        $result = json_decode(
            $this->httpClient->get($venuesUrl)->getBody()->getContents(),
            true
        )['results'];

        $venues = [];
        foreach ($result as $venue) {
            $venues[] = new Venue(
                $venue['id'],
                $venue['name'],
                $venue['address_1']
            );
        }

        return $venues;
    }

    public function getVenueById($venueID)
    {
        $venues = $this->getVenues();
        foreach ($venues as $venue) {

            if ($venue->getId() === (int)$venueID) {
                return $venue;
            }
        }

        return new Venue(null, null, null);
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function createEvent(Event $event)
    {
        // create Meetup.com event
        $this->createMeetup($event);

        // create Joind.in event
        //$this->createJoindinEvent($event);


        // Update DB
        //$this->updateEvents($event);
    }

    public function updateEvents(Event $event)
    {
        $eventEntity = new \App\Model\Event\Entity\Event(
            null,
            $this->meetupEvent->getMeetupEventID(),
            $event->getVenue()->getId(),
            $this->joindinEvent->getTalkID(),
            $this->joindinEvent->getTalkUrl(),
            $event->getTalk()->getSpeaker()->getId(),
            $event->getSponsor()->getId()

        );
        $this->eventsRepository->save($eventEntity);
    }

    /**
     * @param Event $event
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createMeetup(Event $event)
    {
        $response = $this->httpClient->post(
            $this->meetupEvent->getUrl('event'), [
                'form_params' => $this->meetupEvent->getCreateEventPayload($event)
            ]
        );

        $this->meetupEvent->setEventLocation($response->getHeader('location')[0]);
    }


    public function createJoindinEvent(Event $event)
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