<?php

namespace App\Model\Event;

use App\Model\Event\Entity\Speaker;
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

    public function getDetailsByMeetupID($meetupID)
    {
        return $this->eventsRepo->getByMeetupID($meetupID);
    }

}