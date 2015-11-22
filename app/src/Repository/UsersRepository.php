<?php

namespace App\Repository;

use App\Repository\RepositoryAbstract;

class UsersRepository extends RepositoryAbstract
{
    protected $table = "users";

    protected $columns = [
        'id',
        'email',
        'password',
        'role',
        'status'
    ];

    public function getByUserID($userID)
    {
        return $this->getById((int)$userID);
    }

    public function getByEmail(String $email)
    {
        $sql = "SELECT {$this->getColumns()} " .
            "FROM {$this->table} " .
            "WHERE email=:email";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":email", $email, \PDO::PARAM_STR);

        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_OBJ);

        return $stmt->fetch();
    }

    public function save($user)
    {
        $sql = "INSERT INTO {$this->table} (email, password, role, status) VALUES ("
            . ":email, :password, 0, 0"
            . ")";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":email", $user->email, \PDO::PARAM_STR);
        $stmt->bindParam(":password", $user->password, \PDO::PARAM_STR);
        $stmt->execute();
    }

    public function activateUser()
    {

    }

    public function deActivateUser()
    {

    }
}
