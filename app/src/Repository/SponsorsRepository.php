<?php

namespace App\Repository;

use App\Repository\RepositoryAbstract;

use App\Model\Event\Entity\Sponsor;

class SponsorsRepository extends RepositoryAbstract
{
    protected $table = "sponsors";

    protected $columns = [
        'id',
        'name',
        'url',
        'twitter',
        'email',
        'logo'
    ];

    /**
     * @param Sponsor $sponsor
     */
    public function save(Sponsor $sponsor)
    {
        $sql = "INSERT INTO {$this->table} (name, url, twitter, email, logo) VALUES ("
            . ":name, :url, :twitter, :email, :logo"
            . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":name", $sponsor->getName(), \PDO::PARAM_STR);
        $stmt->bindParam(":url", $sponsor->getUrl(),  \PDO::PARAM_STR);
        $stmt->bindParam(":twitter", $sponsor->getTwitter(),  \PDO::PARAM_STR);
        $stmt->bindParam(":email", $sponsor->getEmail(),  \PDO::PARAM_STR);
        $stmt->bindParam(":logo", $sponsor->getLogo(),  \PDO::PARAM_STR);

        $stmt->execute();

        $sponsor->id = $this->db->lastInsertId();
    }

    /**
     * @param $sponsorID
     * @return Sponsor
     */
    public function getSponsorById($sponsorID) : Sponsor
    {
        return Sponsor::create($this->getById($sponsorID));
    }
}