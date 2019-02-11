<?php

namespace PHPMinds\Model\Event\Entity;

class Event
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    private $meetupID;

    /**
     * @var int
     */
    private $meetupVenueID;

    /**
     * @var string
     */
    private $joindinEventName;

    /**
     * @var int
     */
    private $joindinTalkID;

    /**
     * @var string
     */
    private $joindinURL;

    /**
     * @var int
     */
    private $speakerID;

    /**
     * @var int
     */
    private $supporterID;

    /**
     * @var \DateTime
     */
    private $meetupDate;

    public function __construct($meetupID, $meetupVenueID, $joindinEventName, $joindinTalkID, $joindinURL, $speakerID, $supporterID, \DateTime $meetupDate)
    {
        $this->meetupID         = $meetupID;
        $this->meetupVenueID    = $meetupVenueID;
        $this->joindinEventName = $joindinEventName;
        $this->joindinTalkID    = $joindinTalkID;
        $this->joindinURL       = $joindinURL;
        $this->speakerID        = $speakerID;
        $this->supporterID      = $supporterID;
        $this->meetupDate       = $meetupDate;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getJoindinEventName()
    {
        return $this->joindinEventName;
    }

    /**
     * @param mixed $joindinEventName
     */
    public function setJoindinEventName($joindinEventName)
    {
        $this->joindinEventName = $joindinEventName;
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMeetupID()
    {
        return $this->meetupID;
    }

    /**
     * @return int
     */
    public function getMeetupVenueID()
    {
        return $this->meetupVenueID;
    }

    /**
     * @return int
     */
    public function getJoindinTalkID()
    {
        return $this->joindinTalkID;
    }

    /**
     * @return string
     */
    public function getJoindinURL()
    {
        return $this->joindinURL;
    }

    /**
     * @return int
     */
    public function getSpeakerID()
    {
        return $this->speakerID;
    }

    /**
     * @return int
     */
    public function getSupporterID()
    {
        return $this->supporterID;
    }

    /**
     * @return \DateTime
     */
    public function getMeetupDate()
    {
        return $this->meetupDate;
    }

    /**
     * @param array $params
     * @return Event
     */
    public static function create(array $params = []) : Event
    {
        $class = new self(
            $params['meetup_id'] ?? null,
            $params['meetup_venue_id'] ?? null,
            $params['joindin_event_name'] ?? null,
            $params['joindin_talk_id'] ?? null,
            $params['joindin_url'] ?? null,
            $params['speaker_id'] ?? null,
            $params['supporter_id'] ?? null,
            $params['meetup_date'] ?? null
        );

        $class->setId($params['id']  ?? null);

        return $class;
    }
}