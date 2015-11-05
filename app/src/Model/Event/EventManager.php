<?php

namespace App\Model\Event;

use App\Repository\EventsRepository;
use App\Repository\SpeakersRepository;
use App\Model\Event\Entity\Speaker;
use App\Repository\SponsorsRepository;

class EventManager
{

    private $eventsRepo;

    /**
     * @var SpeakersRepository
     */
    private $speakersRepo;

    /**
     * @var SponsorsRepository
     */
    private $sponsorsRepo;

    public function __construct(EventsRepository $eventsRepo, SpeakersRepository $speakersRepo, SponsorsRepository $sponsorsRepo)
    {
        $this->eventsRepo   = $eventsRepo;
        $this->speakersRepo = $speakersRepo;
        $this->sponsorsRepo = $sponsorsRepo;
    }

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
     * @param $sponsorID
     * @return Entity\Sponsor
     */
    public function getSponsorById($sponsorID)
    {
        return $this->sponsorsRepo->getSponsorById($sponsorID);
    }

}