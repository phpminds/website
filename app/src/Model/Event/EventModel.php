<?php

namespace PHPMinds\Model\Event;

use PHPMinds\Model\Event\Entity\Supporter;
use PHPMinds\Model\Event\Entity\Venue;
use PHPMinds\Model\Event\Entity\Talk;

class EventModel
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

    /**
     * @var string
     */
    private $mindsUrl;

    /**
     * @var
     */
    private $meetupID;

    /**
     * @var
     */
    private $meetupURL;

    public function __construct(TalkInterface $talk, \DateTime $date, VenueInterface $venue, SupporterInterface $supporter)
    {
        $this->talk         = $talk;
        $this->date         = $date;
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
    public function getTalk() : TalkInterface
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
     * @return string
     */
    public function getDateTimeAsString()
    {
        return $this->date->format('F jS Y');
    }

    /**
     * @return Venue
     */
    public function getVenue() : VenueInterface
    {
        return $this->venue;
    }

    /**
     * @return Supporter
     */
    public function getSupporter() : SupporterInterface
    {
        return $this->supporter;
    }

    /**
     * @return string
     */
    public function getMindsUrl()
    {
        return $this->mindsUrl;
    }

    /**
     * @param string $mindsUrl
     */
    public function setMindsUrl($mindsUrl)
    {
        $this->mindsUrl = $mindsUrl;
    }

    /**
     * @return mixed
     */
    public function getMeetupID()
    {
        return $this->meetupID;
    }

    /**
     * @param mixed $meetupID
     */
    public function setMeetupID($meetupID)
    {
        $this->meetupID = $meetupID;
    }

    /**
     * @return mixed
     */
    public function getMeetupURL()
    {
        return $this->meetupURL;
    }

    /**
     * @param mixed $meetupURL
     */
    public function setMeetupURL($meetupURL)
    {
        $this->meetupURL = $meetupURL;
    }

    /**
     * An event exists (locally) if it has a Speaker associated with it.
     * @return bool
     */
    public function eventExists()
    {
        return (bool)$this->getTalk()->getSpeaker()->getId();
    }
}