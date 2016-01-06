<?php

namespace PHPMinds\Service;


use DMS\Service\Meetup\MeetupKeyAuthClient;
use PHPMinds\Config\MeetupConfig;
use PHPMinds\Factory\EventFactory;
use PHPMinds\Model\Event\Entity\Venue;
use PHPMinds\Model\Event\EventModel;
use PHPMinds\Model\MeetupEvent;

class MeetupService
{

    /**
     * @var MeetupKeyAuthClient
     */
    protected $client;

    /**
     * @var MeetupEvent
     */
    protected $meetupEvent;

    /**
     * @var MeetupConfig
     */
    protected $config;

    public function __construct(MeetupKeyAuthClient $meetupClient,  MeetupEvent $meetupEvent, MeetupConfig $config)
    {
        $this->client       = $meetupClient;
        $this->meetupEvent  = $meetupEvent;
        $this->config       = $config;
    }

    public function getMeetupEvent()
    {
        return $this->meetupEvent;
    }

    protected function getEvents($args = ['status' => 'past,upcoming'])
    {
        $eventArgs = array_merge(['group_urlname' => $this->config->groupUrlName], $args);
        return $this->client->getEvents($eventArgs)->getData();
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $result = $this->getEvents();

        $events = [];
        foreach ($result as $event) {

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
        $events = $this->getEvents(['status' => 'upcoming']);

        return $this->meetupEvent->formatResponse($events[0] ?? []);
    }

    /**
     * get all events apart form last one in array
     * @return array
     */
    public function getPastEvents($savedEvents = null)
    {
        $pastEvents = [];

        $events = $this->getEvents(['status' => 'past']);

        foreach($events as $event){
            if (!is_null($savedEvents)) {
                if (isset($savedEvents[$event['id']])) {

                    $pastEvents[] = EventFactory::getMergedFromArrays(
                        $this->meetupEvent->formatResponse($event),
                        $savedEvents[$event['id']]
                    );
                } else {
                    $pastEvents[] = EventFactory::getMergedFromArrays(
                        $this->meetupEvent->formatResponse($event),
                        null
                    );
                }
            } else {
                $pastEvents[] = EventFactory::getMergedFromArrays(
                    $this->meetupEvent->formatResponse($event),
                    null
                );
            }
        }

        return $pastEvents;
    }
    /**
     * @param EventModel $event
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createMeetup(EventModel $event)
    {
        $eventArgs = array_merge([
            'group_urlname' => $this->config->groupUrlName],
            $this->meetupEvent->getCreateEventPayload($event)
        );
        $response = $this->client->createEvent($eventArgs);

        $this->meetupEvent->setEventLocation($response->getLocation());

        return $response;
    }

    public function getEventById($eventID = null)
    {
        if (!is_null($eventID)) {
            $result = $this->client->getEvent(['id' => $eventID, 'group_urlname' => $this->config->groupUrlName])->getData();
        }

        return $this->meetupEvent->formatResponse($result ?? []);
    }

    /**
     * @return array
     */
    public function getVenues()
    {
        $result = $this->client->getVenues(['group_urlname' => $this->config->groupUrlName])->getData();

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