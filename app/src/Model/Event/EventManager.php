<?php

namespace App\Model\Event;

use App\Model\Event\Entity\Speaker;
use App\Model\Event\Entity\Event AS EventEntity;
use App\Repository\EventsRepository;
use App\Repository\SpeakersRepository;
use App\Repository\SupportersRepository;

class EventManager
{

    /**
     * @var EventsRepository
     */
    private $eventsRepo;

    /**
     * @var SpeakersRepository
     */
    private $speakersRepo;

    /**
     * @var SupportersRepository
     */
    private $supportersRepo;

    public function __construct(EventsRepository $eventsRepo, SpeakersRepository $speakersRepo, SupportersRepository $supportersRepo)
    {
        $this->eventsRepo   = $eventsRepo;
        $this->speakersRepo = $speakersRepo;
        $this->supportersRepo = $supportersRepo;
    }

    /**
     * @return array
     */
    public function getSpeakers()
    {
        return $this->speakersRepo->getAllSpeakers();
    }

    /**
     * @param $speakerID
     * @return Speaker
     */
    public function getSpeakerById($speakerID)
    {
        return $this->speakersRepo->getBySpeakerID($speakerID);
    }

    /**
     * @return array
     */
    public function getSupporters()
    {
        return $this->supportersRepo->getAllSupporters();
    }

    /**
     * @param $supporterID
     * @return Entity\Supporter
     */
    public function getSupporterByID($supporterID)
    {
        return $this->supportersRepo->getSupporterByID($supporterID);
    }

    /**
     * @param $meetupID
     * @return array
     */
    public function getDetailsByMeetupID($meetupID)
    {
        return $this->eventsRepo->getByMeetupID($meetupID);
    }

    /**
     * @param EventEntity $event
     */
    public function saveEvent(EventEntity $event)
    {
        $this->eventsRepo->save($event);
    }

    /**
     * @param $eventName
     * @return bool
     */
    public function eventExists($eventName)
    {
        return $this->eventsRepo->eventExists($eventName);
    }

    /**
     * @param $meetupID
     * @return bool
     */
    public function getByMeetupID($meetupID)
    {
        return $this->eventsRepo->getByMeetupID($meetupID);
    }

    /**
     * @return array
     */
    public function getAllEvents()
    {
        return $this->eventsRepo->getAll();
    }

    /**
     * @return array
     */
    public function getAllPendingEvents()
    {
        $pendingEvents = $this->eventsRepo->getAllPending();

        return $pendingEvents;
    }

}