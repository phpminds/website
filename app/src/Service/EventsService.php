<?php

namespace App\Service;

use App\Model\Event\Entity\Speaker;
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

    /**
     * @return MeetupEvent
     */
    public function getMeetupEvent()
    {
        return $this->meetupEvent;
    }

    /**
     * @return array
     */
    public function getLatestEvent()
    {
        $events = $this->getEvents();

        return $this->meetupEvent->formatResponse($events['results'][0] ?? []);
    }

    protected function getEvents()
    {
        $eventUrl = $this->meetupEvent->getEventUrl();
        $response = $this->httpClient->get($eventUrl);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getEventById($eventID)
    {
        $eventUrl = sprintf(
                'https://api.meetup.com/%s/events/%s',
                $this->meetupEvent->getGroupUrlName(),
                $eventID
        );

        try {
            $response = $this->httpClient->get(
                $eventUrl,
                [
                    'headers' => [
                        'Accept' => 'application/json'
                    ]
                ]
            );
        } catch (\Exception $e) {
            return [];
        }

        $result = json_decode($response->getBody()->getContents(), true);

        return $this->meetupEvent->formatResponse($result ?? []);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $result = $events = $this->getEvents();

        $events = [];
        foreach ($result['results'] as $event) {
            $eventInfo = $this->meetupEvent->formatResponse($event);
            $events[$eventInfo['id']] = $eventInfo;
        }

        return $events;
    }

    /**
     * @param $meetupEvents
     * @param $speakers
     * @param $venues
     */
    public function mergeEvents(&$meetupEvents, $speakers, $venues)
    {
        // key it on meetup ID
        $localEvents = array_reduce($this->eventsRepository->getAll(), function($carry, $item) {
            $carry[$item->meetup_id] = $item;
            return $carry;
        });

        if (empty($localEvents)) {
            return;
        }

        // Use only events which exist on the DB
        $meetupEvents = array_intersect_key($meetupEvents, $localEvents);
        foreach ($localEvents as $event) {
            if (array_key_exists($event->meetup_id, $meetupEvents)) {

                // check for speaker
                if (array_key_exists($event->speaker_id, $speakers)) {
                    /** @var Speaker $speaker */
                    $speaker = $speakers[$event->speaker_id];
                    $meetupEvents[$event->meetup_id]['speaker'] = $speaker->getFirstName() . ' '
                                    . $speaker->getLastName() . ' (' . $speaker->getTwitter() . ')';
                } else {
                    $meetupEvents[$event->meetup_id]['speaker'] = '-';
                }

                $meetupEvents[$event->meetup_id]['joindin_url'] = $event->joindin_url ?? '-';

            }
        }
    }

    /**
     * @return array
     */
    public function getVenues()
    {
        $venuesUrl = $this->meetupEvent->getVenuesUrl();

        $result = json_decode(
            $this->httpClient->get($venuesUrl)->getBody()->getContents(),
            true
        )['results'];


        $venues = [];
        foreach ($result as $venue) {
            $venueInfo = Venue::create(
                [
                    'id' => $venue['id'],
                    'name' => $venue['name'],
                    'address' => $venue['address_1']
                ]
            );

            $venues[$venueInfo->getId()] = $venueInfo;
        }

        return $venues;
    }

    /**
     * @param $venueID
     * @return Venue
     */
    public function getVenueById($venueID)
    {
        $venues = $this->getVenues();
        foreach ($venues as $venue) {
            /** @var Venue $venue */
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
    }

    /**
     * Save event references to the DB
     *
     */
    public function updateEvents()
    {
        $eventEntity = new \App\Model\Event\Entity\Event(
            $this->meetupEvent->getMeetupEventID(),
            $this->event->getVenue()->getId(),
            $this->event->getName(),
            $this->joindinEvent->getTalkID(),
            $this->joindinEvent->getTalkUrl(),
            $this->event->getTalk()->getSpeaker()->getId(),
            $this->event->getSupporter()->getId()
        );

        $this->eventsRepository->save($eventEntity);

        return $eventEntity;
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

        return $response;
    }

    /**
     * @param $userID
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createJoindinEvent($userID)
    {
        $response = $this->httpClient->post(
            $this->joindinEvent->getUrl('events'), [
            'json' => $this->joindinEvent->getCreateEventPayload($this->event),
            'headers' => $this->joindinEvent->getHeaders($userID)
        ]);

        $this->joindinEvent->setEventLocation($response->getHeader('location')[0]);

        return $response;
    }

    /**
     * @param string $language
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createJoindinTalk($language = 'English - UK')
    {
        $response = $this->httpClient->post(
            $this->joindinEvent->getUrl('events/' . $this->joindinEvent->getJoindinEventID() .'/talks'), [
            'json' => $this->joindinEvent->getCreateEventTitlePayload($this->event, $language),
            'headers' => $this->joindinEvent->getHeaders()
        ]);

        $this->joindinEvent->setTalkLocation($response->getHeader('location')[0]);

        return $response;
    }

    /**
     * @param $meetupID
     * @return array
     */
    public function getEventInfo($meetupID) : array
    {
        return $this->eventsRepository->getByMeetupID($meetupID)[0] ?: [];
    }
}