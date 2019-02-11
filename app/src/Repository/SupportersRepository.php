<?php

namespace PHPMinds\Repository;

use PHPMinds\Model\Event\SupporterInterface;
use PHPMinds\Repository\RepositoryAbstract;

use PHPMinds\Model\Event\Entity\Supporter;

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
        $stmt->bindValue(":name", $supporter->getName(), \PDO::PARAM_STR);
        $stmt->bindValue(":url", $supporter->getUrl(),  \PDO::PARAM_STR);
        $stmt->bindValue(":twitter", $supporter->getTwitter(),  \PDO::PARAM_STR);
        $stmt->bindValue(":email", $supporter->getEmail(),  \PDO::PARAM_STR);
        $stmt->bindValue(":logo", $supporter->getLogo(),  \PDO::PARAM_STR);

        $stmt->execute();

        $supporter->id = $this->db->lastInsertId();
    }

    /**
     * @return array
     */
    public function getAllSupporters()
    {
        $results = $this->getAll(\PDO::FETCH_ASSOC);
        $supporters = [];
        foreach ($results as $supporter) {
            $supporters[] = Supporter::create($supporter);
        }

        return $supporters;
    }

    /**
     * @param int $supporterID
     * @return SupporterInterface
     * @throws \PHPMinds\Exception\Model\InvalidEmailException
     * @throws \PHPMinds\Exception\Model\InvalidTwitterHandleException
     */
    public function getSupporterByID(int $supporterID) : SupporterInterface
    {
        return Supporter::create($this->getById($supporterID));
    }
}