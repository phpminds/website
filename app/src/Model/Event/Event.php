<?php

namespace App\Model\Event;

use App\Model\Event\Entity\Supporter;
use App\Model\Event\Entity\Venue;
use App\Model\Event\Entity\Talk;

class Event
{
    private $name;

    private $description;


    /**
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
     * @var Supporter
     */
    private $supporter;


    public function __construct(Talk $talk, $startDate, $startTime, Venue $venue, Supporter $supporter)
    {
        $this->talk         = $talk;
        $this->date         = \DateTime::createFromFormat("d/m/Y H:i", $startDate . ' ' . $startTime);
        $this->venue        = $venue;
        $this->supporter    = $supporter;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name  . ' ' . $this->getDate()->format('F Y');
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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

    public function getEndDate()
    {
        return $this->date->add($this->talk->getDuration());
    }

    /**
     * @return Venue
     */
    public function getVenue() : Venue
    {
        return $this->venue;
    }

    /**
     * @return Supporter
     */
    public function getSupporter() : Supporter
    {
        return $this->supporter;
    }
}