<?php

namespace App\Model\Event;

use App\Repository\EventsRepository;
use App\Repository\SpeakersRepository;

class EventManager
{

    private $eventsRepo;

    private $speakersRepo;

    public function __construct(EventsRepository $eventsRepo, SpeakersRepository $speakersRepo)
    {
        $this->eventsRepo = $eventsRepo;
        $this->speakersRepo = $speakersRepo;
    }

    public function getSpeakers()
    {
        return $this->speakersRepo->getAllSpeakers();
    }

}