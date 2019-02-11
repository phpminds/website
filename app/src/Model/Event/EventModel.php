<?php

namespace PHPMinds\Model\Event;

use PHPMinds\Model\Event\Entity\Supporter;
use PHPMinds\Model\Event\Entity\Venue;
use PHPMinds\Model\Event\Entity\Talk;

class EventModel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var TalkInterface
     */
    private $talk;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var VenueInterface
     */
    private $venue;

    /**
     * @var SupporterInterface
     */
    private $supporter;

    /**
     * @var string
     */
    private $mindsUrl;

    /**
     * @var int
     */
    private $meetupID;

    /**
     * @var string
     */
    private $meetupURL;

    /**
     * @param TalkInterface      $talk
     * @param \DateTime          $date
     * @param VenueInterface     $venue
     * @param SupporterInterface $supporter
     */
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
     * @return TalkInterface
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
     * @return VenueInterface
     */
    public function getVenue() : VenueInterface
    {
        return $this->venue;
    }

    /**
     * @return SupporterInterface
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

    public function isRegistered()
    {
        return isset($this->name);
    }

    /**
     * An event exists (locally) if it has a Speaker associated with it.
     * @return bool
     */
    public function eventExists()
    {
        return $this->getTalk()->getSpeaker()->exists();
    }
}
