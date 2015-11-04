<?php

namespace App\Model\Event;

use App\Model\Event\Entity\Venue;
use App\Model\Event\Talk;

class Event
{
    /**
     * Collection of talks.
     *
     * @var Talk[]
     */
    private $talks = [];

    private $startDate;

    private $endDate;

    /**
     * @var Venue
     */
    private $venue;


    public function __construct(Talk $talk, $startDate, $endDate, $venue)
    {
        $this->talks[] = $talk;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->venue = $venue;
    }

    /**
     * Expecting an instance of Talks
     *
     * @param array $talks
     * @throws \Exception
     */
    public function addTalks(array $talks)
    {
        foreach ($talks as $talk) {
            if ( !($talk instanceof Talk)) {
                throw new \Exception('');
            }
            $this->talks[] = $talk;
        }
    }

    /**
     * @return Talk[]
     */
    public function getTalks()
    {
        return $this->talks;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return \App\Model\Event\Venue
     */
    public function getVenue()
    {
        return $this->venue;
    }

}