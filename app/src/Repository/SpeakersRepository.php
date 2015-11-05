<?php

namespace App\Repository;

use App\Repository\RepositoryAbstract;

use App\Model\Event\Entity\Speaker;

class SpeakersRepository extends RepositoryAbstract
{
    protected $table = "speakers";

    protected $columns = [
        'id',
        'first_name',
        'last_name',
        'email',
        'twitter',
        'avatar'
    ];

    /**
     * @param Speaker $speaker
     */
    public function save(Speaker $speaker)
    {
        $sql = "INSERT INTO {$this->table} (first_name, last_name, email, twitter) VALUES ("
            . ":first_name, :last_name, :email, :twitter"
            . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":first_name", $speaker->getFirstName(), \PDO::PARAM_STR);
        $stmt->bindParam(":last_name", $speaker->getLastName(),  \PDO::PARAM_STR);
        $stmt->bindParam(":email", $speaker->getEmail(),  \PDO::PARAM_STR);
        $stmt->bindParam(":twitter", $speaker->getTwitter(),  \PDO::PARAM_STR);

        $stmt->execute();

        $speaker->id = $this->db->lastInsertId();
    }

    /**
     * @return array $speakers
     */
    public function getAllSpeakers()
    {
        $results = $this->getAll(\PDO::FETCH_ASSOC);
        $speakers = [];
        foreach ($results as $speaker) {
            $speakers[] = Speaker::create($speaker);
        }

        return $speakers;
    }

    /**
     * @param $speakerID
     * @return Speaker
     */
    public function getBySpeakerID($speakerID) : Speaker
    {
        return Speaker::create($this->getById($speakerID));
    }
}