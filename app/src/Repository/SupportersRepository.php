<?php

namespace App\Repository;

use App\Repository\RepositoryAbstract;

use App\Model\Event\Entity\Sponsor;

class SupportersRepository extends RepositoryAbstract
{
    protected $table = "supporters";

    protected $columns = [
        'id',
        'name',
        'url',
        'twitter',
        'email',
        'logo'
    ];

    /**
     * @param Supporter $supporter
     */
    public function save(Supporter $supporter)
    {
        $sql = "INSERT INTO {$this->table} (name, url, twitter, email, logo) VALUES ("
            . ":name, :url, :twitter, :email, :logo"
            . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":name", $supporter->getName(), \PDO::PARAM_STR);
        $stmt->bindParam(":url", $supporter->getUrl(),  \PDO::PARAM_STR);
        $stmt->bindParam(":twitter", $supporter->getTwitter(),  \PDO::PARAM_STR);
        $stmt->bindParam(":email", $supporter->getEmail(),  \PDO::PARAM_STR);
        $stmt->bindParam(":logo", $supporter->getLogo(),  \PDO::PARAM_STR);

        $stmt->execute();

        $supporter->id = $this->db->lastInsertId();
    }

    /**
     * @param $supporterID
     * @return Supporter
     */
    public function getSponsorById($supporterID) : Supporter
    {
        return Supporter::create($this->getById($supporterID));
    }
}