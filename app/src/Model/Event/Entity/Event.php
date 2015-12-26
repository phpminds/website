<?php

namespace PHPMinds\Model\Event\Entity;

class Event
{
    public $id;

    private $meetupID;

    private $meetupVenueID;

    private $joindinEventName;

    private $joindinTalkID;

    private $joindinURL;

    private $speakerID;

    private $supporterID;

    public function __construct($meetupID, $meetupVenueID, $joindinEventName, $joindinTalkID, $joindinURL, $speakerID, $supporterID)
    {
        $this->meetupID         = $meetupID;
        $this->meetupVenueID    = $meetupVenueID;
        $this->joindinEventName = $joindinEventName;
        $this->joindinTalkID    = $joindinTalkID;
        $this->joindinURL       = $joindinURL;
        $this->speakerID        = $speakerID;
        $this->supporterID      = $supporterID;
    }

    /**
     * @param $id
     */
    public function setId($id)
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMeetupID()
    {
        return $this->meetupID;
    }

    /**
     * @return mixed
     */
    public function getMeetupVenueID()
    {
        return $this->meetupVenueID;
    }

    /**
     * @return mixed
     */
    public function getJoindinTalkID()
    {
        return $this->joindinTalkID;
    }

    /**
     * @return mixed
     */
    public function getJoindinURL()
    {
        return $this->joindinURL;
    }

    /**
     * @return mixed
     */
    public function getSpeakerID()
    {
        return $this->speakerID;
    }

    /**
     * @return mixed
     */
    public function getSupporterID()
    {
        return $this->supporterID;
    }

    public static function create(array $params = []) : Event
    {
        $class = new self(
            $params['meetup_id'] ?? null,
            $params['meetup_venue_id'] ?? null,
            $params['joindin_event_name'] ?? null,
            $params['joindin_talk_id'] ?? null,
            $params['joindin_url'] ?? null,
            $params['speaker_id'] ?? null,
            $params['supporter_id'] ?? null
        );

        $class->setId($params['id']  ?? null);

        return $class;
    }
}