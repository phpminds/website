<?php

namespace App\Model\Event;

use App\Model\Event\Entity\Sponsor;
use App\Model\Event\Entity\Venue;
use App\Model\Event\Entity\Talk;

class Event
{
    /**
     * Collection of talks.
     *
     * @var Talk
     */
    private $talk;

    private $date;

    /**
     * @var Venue
     */
    private $venue;

    /**
     * @var Sponsor
     */
    private $sponsor;


    public function __construct(Talk $talk, $startDate, $startTime, Venue $venue, Sponsor $sponsor)
    {
        $this->talk     = $talk;
        $this->date     = new \DateTime($startDate . ' ' . $startTime);
        $this->venue    = $venue;
        $this->sponsor  = $sponsor;
    }

    /**
     * @return Talk
     */
    public function getTalk() : Talk
    {
        return $this->talk;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return Venue
     */
    public function getVenue() : Venue
    {
        return $this->venue;
    }

    public function getSponsor() : Sponsor
    {
        return $this->sponsor;
    }
}