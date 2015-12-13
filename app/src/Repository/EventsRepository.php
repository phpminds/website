<?php

namespace App\Repository;

use App\Model\Event\Entity\Venue;
use App\Repository\RepositoryAbstract;

use App\Model\Event\Entity\Event;

class EventsRepository extends RepositoryAbstract
{
    protected $table = "events";

    protected $columns = [
        'id',
        'meetup_id',
        'meetup_venue_id',
        'joindin_event_name',
        'joindin_talk_id',
        'joindin_url',
        'speaker_id',
        'supporter_id'
    ];

    /**
     * @param Event $event
     * @return bool
     */
    public function save(Event $event)
    {
        if ($this->eventExists($event->getJoindinEventName())) {
            return $this->update($event);
        }

        $sql = "INSERT INTO {$this->table} (meetup_id, meetup_venue_id, joindin_event_name, joindin_talk_id, joindin_url, speaker_id, supporter_id) VALUES ("
            . ":meetup_id, :meetup_venue_id, :joindin_event_name, :joindin_talk_id, :joindin_url, :speaker_id, :supporter_id"
            . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":meetup_id", $event->getMeetupID(), \PDO::PARAM_INT);
        $stmt->bindParam(":meetup_venue_id", $event->getMeetupVenueID(), \PDO::PARAM_INT);
        $stmt->bindParam(":joindin_event_name", $event->getJoindinEventName(), \PDO::PARAM_STR);
        $stmt->bindParam(":joindin_talk_id", $event->getJoindinTalkID(), \PDO::PARAM_INT);
        $stmt->bindParam(":joindin_url", $event->getJoindinURL(), \PDO::PARAM_STR);
        $stmt->bindParam(":speaker_id", $event->getSpeakerID(), \PDO::PARAM_INT);
        $stmt->bindParam(":supporter_id", $event->getSupporterID(), \PDO::PARAM_INT);


        $stmt->execute();

        $event->id = $this->db->lastInsertId();
    }

    public function update(Event $event)
    {
        $sql = 'UPDATE ' . $this->table
            . ' SET meetup_venue_id = :meetup_venue_id, joindin_event_name = :joindin_event_name,'
            . ' joindin_talk_id = :joindin_talk_id, joindin_url = :joindin_url, speaker_id = :speaker_id,'
            . ' supporter_id = :supporter_id'
            . ' WHERE meetup_id = :meetup_id';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":meetup_id", $event->getMeetupID(), \PDO::PARAM_INT);
        $stmt->bindParam(":meetup_venue_id", $event->getMeetupVenueID(), \PDO::PARAM_INT);
        $stmt->bindParam(":joindin_event_name", $event->getJoindinEventName(), \PDO::PARAM_STR);
        $stmt->bindParam(":joindin_talk_id", $event->getJoindinTalkID(), \PDO::PARAM_INT);
        $stmt->bindParam(":joindin_url", $event->getJoindinURL(), \PDO::PARAM_STR);
        $stmt->bindParam(":speaker_id", $event->getSpeakerID(), \PDO::PARAM_INT);
        $stmt->bindParam(":supporter_id", $event->getSupporterID(), \PDO::PARAM_INT);


        return $stmt->execute();
    }

    public function getByMeetupID($meetupID)
    {
        $aliasedCols = $this->columns;

        array_walk($aliasedCols, function(&$value, $key, $alias){
            $value = $alias. '.'.$value;
        }, 'ev');


        $sql = 'SELECT ' . implode(', ', $aliasedCols)
            . ', sp.first_name, sp.last_name, sp.email, sp.twitter, sp.avatar'
            . ', supp.name AS supporter_name, supp.url AS supporter_url, supp.twitter AS supporter_twitter'
            . ', supp.email AS supporter_email, supp.logo AS supporter_logo'
            . " FROM {$this->table} AS ev"
            . " LEFT JOIN `speakers` AS sp ON sp.id = ev.speaker_id"
            . " LEFT JOIN `supporters` AS supp ON supp.id = ev.supporter_id"
            . " WHERE meetup_id= :meetup_id";


        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":meetup_id", $meetupID, \PDO::PARAM_INT);


        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        return $stmt->fetchAll();
    }

    public function eventExists($eventName)
    {
        $sql = 'SELECT COUNT(*)'
                . ' FROM '. $this->table
                . ' WHERE joindin_event_name = :event_name';

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":event_name", $eventName, \PDO::PARAM_STR);

        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        $result = $stmt->fetchColumn();

        return (int)$result[0] > 0;
    }

    /**
     * @return array
     */
    public function getAllPending()
    {
        // a pending event has a joindin_talk_id of ZERO
        $sql = 'SELECT meetup_id, joindin_event_name, speaker_id, supporter_id'
            . ' FROM '. $this->table
            . ' WHERE joindin_talk_id = 0 ';

        return $this->db->query($sql, \PDO::FETCH_OBJ)->fetchAll();
    }
}