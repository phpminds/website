<?php

namespace PHPMinds\Service;

use PHPMinds\Factory\EventFactory;
use PHPMinds\Model\Event\Entity\Speaker;
use PHPMinds\Model\Event\Entity\Talk;
use PHPMinds\Model\Event\EventModel;
use PHPMinds\Model\Event\EventManager;
use PHPMinds\Model\MeetupEvent;


class EventsService
{
    /**
     * @var \GuzzleHttp\Client()
     */
    protected $httpClient;

    /**
     * @var MeetupService
     */
    protected $meetupService;

    /**
     * @var JoindinService
     */
    protected $joindinEventService;

    /**
     * @var EventModel
     */
    protected $event;

    /**
     * @var EventManager
     */
    protected $eventManager;


    public function __construct(MeetupService $meetupService, JoindinService $joindinEventService, EventManager $eventManager)
    {
        $this->meetupService            = $meetupService;
        $this->joindinEventService      = $joindinEventService;
        $this->eventManager             = $eventManager;
    }

    /**
     * @return MeetupEvent
     */
    public function getMeetupEvent()
    {
        return $this->meetupService->getMeetupEvent();
    }

    /**
     * @return \PHPMinds\Model\Event\EventModel
     */
    public function getLatestEvent()
    {
        $event      = $this->meetupService->getLatestEvent();
        $eventInfo  = $this->eventManager->getDetailsByMeetupID($event['id']);
        $eventInfo  = $eventInfo[0] ?? null;

        return EventFactory::getMergedFromArrays($event, $eventInfo);
    }

    /**
     * Get all events except for latest.
     * @return array of \PHPMinds\Model\Event\EventModel
     */
    public function getPastEvents()
    {
        return $this->meetupService->getPastEvents(
            $this->eventManager->getAllEventDetails()
        );
    }


    /**
     * @param $eventID
     * @return \PHPMinds\Model\Event\EventModel
     */
    public function getEventById($eventID)
    {
        $event = $this->meetupService->getEventById($eventID);
        $eventInfo  = $this->eventManager->getDetailsByMeetupID($event['id']);
        $eventInfo  = $eventInfo[0] ?? null;

        return EventFactory::getMergedFromArrays($event, $eventInfo);
    }

    /**
     * @return \PHPMinds\Model\Event\EventModel
     */
    public function getAll()
    {
        $events = $this->meetupService->getAll();
        $eventDetails = $this->eventManager->getAllEventDetails();

        $result = [];
        foreach ($events as $event) {

            if (isset($eventDetails[$event['id']])) {

                $result[] = EventFactory::getMergedFromArrays(
                    $event,
                    $eventDetails[$event['id']]
                );
            } else {
                $result[] = EventFactory::getMergedFromArrays(
                    $event,
                    null
                );
            }
        }

        return $result;
    }

    /**
     * @param int $meetupID
     * @return \PHPMinds\Model\Event\EventModel
     */
    public function getInfoByMeetupID($meetupID = null)
    {
        return $this->getEventById((int)$meetupID);

    }

    /**
     * @param $meetupEvents
     * @param $speakers
     * @param $venues
     */
    public function mergeEvents(&$meetupEvents, $speakers, $venues)
    {
        // key it on meetup ID
        $localEvents = array_reduce($this->eventManager->getAllEvents(), function($carry, $item) {
            $carry[$item->meetup_id] = $item;
            return $carry;
        });

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
        return $this->meetupService->getVenues();
    }

    /**
     * @param $venueID
     * @return \PHPMinds\Model\Event\Entity\Venue
     */
    public function getVenueById($venueID)
    {
        return $this->meetupService->getVenueById($venueID);
    }

    /**
     * @param EventModel $event
     * @param $userID
     * @param null $meetupID
     * @return array
     * @throws \Exception
     */
    public function createMainEvents(EventModel $event, $userID, $meetupID = null)
    {
        $this->createEvent($event);

        if (is_null($meetupID)) {
            if ((int)$this->createMeetup()->getStatusCode() !== 201) {
                throw new \Exception('Could not create meetup event.');
            }
        } else {
            // Do not create a meetup
            $this->getMeetupEvent()->setEventID((int)$meetupID);
        }

        try {
            $joindinEvent = $this->createJoindinEvent($userID);
        } catch (\Exception $e) {
            throw $e;
        }

        $eventEntity = $this->updateEvents();

        return [
            'joindin_status' => $joindinEvent->getStatusCode(),
            'meetup_id' => $eventEntity->getMeetupID()
        ];
    }

    /**
     * @param EventModel $event
     * @return bool
     */
    public function createEvent(EventModel $event)
    {
        $this->event = $event;
    }

    /**
     * Save event references to the DB
     *
     * @param  string $eventName If null, use it through the event object
     * @return \PHPMinds\Model\Event\Entity\Event
     */
    public function updateEvents($eventName = null)
    {
        $eventName = $eventName ?? $this->event->getName();

        $eventEntity = new \PHPMinds\Model\Event\Entity\Event(
            $this->meetupService->getMeetupEvent()->getMeetupEventID(),
            $this->event->getVenue()->getId(),
            $eventName,
            $this->joindinEventService->getJoindinEvent()->getTalkID(),
            $this->joindinEventService->getJoindinEvent()->getTalkUrl(),
            $this->event->getTalk()->getSpeaker()->getId(),
            $this->event->getSupporter()->getId(),
            $this->event->getDate()
        );

        $this->eventManager->saveEvent($eventEntity);

        return $eventEntity;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createMeetup()
    {
        return $this->meetupService->createMeetup($this->event);
    }

    /**
     * @param $userID
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function createJoindinEvent($userID)
    {
        if ($this->eventManager->eventExists($this->event->getName())) {
            throw new \Exception('An event by the name: ' . $this->event->getName() . ', already exists.');
        }

        $this->joindinEventService->setEvent($this->event);
        return $this->joindinEventService->createEvent($userID);
    }

    /**
     * @param int $userID
     * @param string $language
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function createJoindinTalk($userID, $language = 'English - UK')
    {
        return $this->joindinEventService->createTalk($this->event, $userID, $language);
    }

    /**
     * @param $meetupID
     * @return array
     */
    public function getEventInfo($meetupID) : array
    {
        return $this->eventManager->getByMeetupID($meetupID)[0] ?: [];
    }

    /**
     * @param $userID
     * @return string
     * @throws \Exception
     */
    public function manageApprovedEvents($userID)
    {
        $events = $this->eventManager->getAllPendingEvents();

        if (count($events) > 0) {

            if($this->joindinEventService->areEventsApproved($events)) {

                foreach ($events as $eventName => $event) {

                    // API call
                    $meetupEvent = $this->getEventById($event->meetup_id);
                    $this->getMeetupEvent()->setEventID($event->meetup_id);

                    $speaker = $this->eventManager->getSpeakerById($event->speaker_id);
                    $supporter = $this->eventManager->getSupporterByID($event->supporter_id);
                    $venue = $this->getVenueById($meetupEvent['venue_id']);

                    $talk = Talk::create([
                        'title'         => $meetupEvent['subject'],
                        'description'   => $meetupEvent['description'],
                        'speaker'       => $speaker,
                        'duration'      => 'PT2H' // default to 2 hours
                    ]);

                    $this->event = new EventModel(
                        $talk,
                        \DateTime::createFromFormat(
                            "F jS Y g:ia",
                            $meetupEvent['date'] . ' ' . $meetupEvent['time']
                        ),
                        $venue,
                        $supporter
                    );

                    $this->joindinEventService->getJoindinEvent()->setEventLocation($event->uri);
                    if ($this->createJoindinTalk($userID)->getStatusCode() !== 201) {
                        throw new \Exception('Could not create Joindin Talk');
                    }

                    $this->updateEvents($eventName);

                }
            }

            return 'Created [' . count($events) . '] Talks';
        }

        return 'No pending events found.';
    }

}