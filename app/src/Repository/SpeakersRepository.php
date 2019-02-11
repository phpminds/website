<?php

namespace PHPMinds\Repository;

use PHPMinds\Model\Event\SpeakerInterface;
use PHPMinds\Repository\RepositoryAbstract;

use PHPMinds\Model\Event\Entity\Speaker;

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
        $stmt->bindValue(":first_name", $speaker->getFirstName(), \PDO::PARAM_STR);
        $stmt->bindValue(":last_name", $speaker->getLastName(),  \PDO::PARAM_STR);
        $stmt->bindValue(":email", $speaker->getEmail(),  \PDO::PARAM_STR);
        $stmt->bindValue(":twitter", $speaker->getTwitter(),  \PDO::PARAM_STR);

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
            $speakerInfo = Speaker::create($speaker);
            $speakers[$speakerInfo->getId()] = $speakerInfo;
        }

        return $speakers;
    }

    /**
     * @param int $speakerID
     * @return SpeakerInterface
     * @throws \PHPMinds\Exception\Model\InvalidEmailException
     * @throws \PHPMinds\Exception\Model\InvalidTwitterHandleException
     */
    public function getBySpeakerID(int $speakerID) : SpeakerInterface
    {
        return Speaker::create($this->getById($speakerID));
    }
}