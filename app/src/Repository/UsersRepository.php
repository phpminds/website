<?php

namespace App\Model\Repository;

use App\Model\Repository\RepositoryAbstract;

class UsersRepository extends RepositoryAbstract
{
    protected $table = "users";

    protected $columns = [
        'id',
        'username',
        'password',
        'role',
        'status'
    ];

    public function getByUserID($userID)
    {
        return $this->getById((int)$userID);
    }

    public function getByUsername($username)
    {
        $sql = "SELECT {$this->columns} " .
            "FROM {$this->table} " .
            "WHERE username=:username";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":username", $username, \PDO::PARAM_STR);

        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_OBJ);

        return $stmt->fetch();
    }

    public function save($user)
    {
        $sql = "INSERT INTO {$this->table} (username, password, role, status) VALUES ("
            . ":username, :password, 0, 0"
            . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":username", $user->username, \PDO::PARAM_STR);
        $stmt->bindParam(":password", $user->password,  \PDO::PARAM_STR);
        $stmt->execute();
    }

    public function activateUser()
    {

    }

    public function deActivateUser()
    {

    }
}