<?php

namespace App\Repository;

use App\Repository\RepositoryAbstract;

use App\Model\Event\Entity\Event;

class EventsRepository extends RepositoryAbstract
{
    protected $table = "events";

    protected $columns = [
        'id',
        'meetup_id',
        'meetup_venue_id',
        'joindin_talk_id',
        'joindin_url',
        'speaker_id',
        'sponsor_id'
    ];

    /**
     * @param Event $event
     */
    public function save(Event $event)
    {
        $sql = "INSERT INTO {$this->table} (meetup_id, meetup_venue_id, joindin_talk_id, joindin_url, speaker_id, sponsor_id) VALUES ("
            . ":meetup_id, :meetup_venue_id, :joindin_talk_id, :joindin_url, :speaker_id, :sponsor_id"
            . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":meetup_id", $event->getMeetupID(), \PDO::PARAM_STR);
        $stmt->bindParam(":meetup_venue_id", $event->getMeetupID(), \PDO::PARAM_STR);
        $stmt->bindParam(":joindin_talk_id", $event->getMeetupID(), \PDO::PARAM_STR);
        $stmt->bindParam(":joindin_url", $event->getMeetupID(), \PDO::PARAM_STR);
        $stmt->bindParam(":speaker_id", $event->getMeetupID(), \PDO::PARAM_STR);
        $stmt->bindParam(":sponsor_id", $event->getMeetupID(), \PDO::PARAM_STR);


        $stmt->execute();

        $event->id = $this->db->lastInsertId();
    }
}