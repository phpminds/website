<?php

namespace App\Service;


use App\Model\Event\Entity\Venue;
use App\Model\Event\Event;
use App\Model\MeetupEvent;

class MeetupService
{
    /**
     * @var \GuzzleHttp\Client()
     */
    protected $httpClient;

    /**
     * @var MeetupEvent
     */
    protected $meetupEvent;

    public function __construct($httpClient, MeetupEvent $meetupEvent)
    {
        $this->httpClient = $httpClient;
        $this->meetupEvent = $meetupEvent;
    }

    public function getMeetupEvent()
    {
        return $this->meetupEvent;
    }

    protected function getEvents()
    {
        $eventUrl = $this->meetupEvent->getEventUrl();

        $response = $this->httpClient->get($eventUrl);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $result = $this->getEvents();

        $events = [];
        foreach ($result['results'] as $event) {
            $eventInfo = $this->meetupEvent->formatResponse($event);
            $events[$eventInfo['id']] = $eventInfo;
        }

        return $events;
    }

    /**
     * @return array
     */
    public function getLatestEvent()
    {
        $events = $this->getEvents();

        return $this->meetupEvent->formatResponse($events['results'][0] ?? []);
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

        return $response;
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

}