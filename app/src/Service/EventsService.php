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

    /**
     * @var Event
     */
    private $event;

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
            $venues[] = Venue::create(
                [
                    'id' => $venue['id'],
                    'name' => $venue['name'],
                    'address' => $venue['address_1']
                ]
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
        $this->event = $event;

        // create Meetup.com event
        //$this->createMeetup();

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
            $event->getSupporter()->getId()

        );
        $this->eventsRepository->save($eventEntity);
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createMeetup()
    {
        $response = $this->httpClient->post(
            $this->meetupEvent->getUrl('event'), [
                'form_params' => $this->meetupEvent->getCreateEventPayload($this->event)
            ]
        );

        $this->meetupEvent->setEventLocation($response->getHeader('location')[0]);
    }


    public function createJoindinEvent($eventName, $eventDescription)
    {
        $response = $this->httpClient->post(
            $this->joindinEvent->getUrl('events'), [
            'json' => $this->joindinEvent->getCreateEventPayload($this->event, $eventName, $eventDescription),
            'headers' => $this->joindinEvent->getHeaders()
        ]);

        $this->joindinEvent->setEventLocation($response->getHeader('location')[0]);

        return $response;
    }

    public function createJoindinTalk($language = 'English - UK')
    {
        $talkResponse = $this->httpClient->post(
            $this->joindinEvent->getUrl('events/' . $this->joindinEvent->getJoindinEventID() .'/talks'), [
            'json' => $this->joindinEvent->getCreateEventTitlePayload($this->event, $language),
            'headers' => $this->joindinEvent->getHeaders()
        ]);

        return $talkResponse;
    }
}