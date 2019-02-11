<?php

namespace PHPMinds\Model\Event;

use PHPMinds\Model\Event\Entity\Speaker;
use PHPMinds\Model\Event\Entity\Event AS EventEntity;
use PHPMinds\Repository\EventsRepository;
use PHPMinds\Repository\SpeakersRepository;
use PHPMinds\Repository\SupportersRepository;

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
     * @param int $speakerID
     * @return SpeakerInterface
     */
    public function getSpeakerById(int $speakerID)
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
     * @param int $supporterID
     * @return SupporterInterface
     */
    public function getSupporterByID(int $supporterID)
    {
        return $this->supportersRepo->getSupporterByID($supporterID);
    }

    /**
     * @param int|null $meetupID
     * @return array
     */
    public function getDetailsByMeetupID(int $meetupID = null)
    {
        return $this->eventsRepo->getByMeetupID($meetupID);
    }

    /**
     * @return array
     */
    public function getAllEventDetails()
    {
        return $this->eventsRepo->getAllEventDetails();
    }

    /**
     * @param EventEntity $event
     */
    public function saveEvent(EventEntity $event)
    {
        $this->eventsRepo->save($event);
    }

    /**
     * @param string $eventName
     * @return bool
     */
    public function eventExists(string $eventName)
    {
        return $this->eventsRepo->eventExists($eventName);
    }

    /**
     * @param int $meetupID
     * @return array
     */
    public function getByMeetupID(int $meetupID)
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

    /**
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getByYearMonth(int $year, int $month)
    {
        $event = $this->eventsRepo->getEventByYearAndMonth($year,$month);

        return $event;
    }
}