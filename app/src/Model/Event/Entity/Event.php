<?php

namespace App\Model\Event\Entity;

class Event
{
    public $id;

    private $meetupID;

    private $meetupVenueID;

    private $joindinTalkID;

    private $joindinURL;

    private $speakerID;

    private $sponsorID;

    public function __construct($id, $meetupID, $meetupVenueID, $joindinTalkID, $joindinURL, $speakerID, $sponsorID)
    {
        $this->id               = $id;
        $this->meetupID         = $meetupID;
        $this->meetupVenueID    = $meetupVenueID;
        $this->joindinTalkID    = $joindinTalkID;
        $this->joindinURL       = $joindinURL;
        $this->speakerID        = $speakerID;
        $this->sponsorID        = $sponsorID;
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
    public function getSponsorID()
    {
        return $this->sponsorID;
    }

    public static function create(array $params = []) : Event
    {
        return new self(
            $params['id'],
            $params['meetup_id'],
            $params['meetup_venue_id'],
            $params['joindin_talk_id'],
            $params['joindin_url'],
            $params['speaker_id'],
            $params['sponsor_id']
        );
    }
}