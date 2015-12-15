<?php

namespace App\Repository;

class RepositoryAbstract
{
    /**
     * @var \PDO
     */
    protected $db;

    protected $table;

    protected $columns = [];

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    protected function getById($id)
    {
        $sql = "SELECT {$this->getColumns()} " .
                "FROM {$this->table} " .
                "WHERE id=:id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $id, \PDO::PARAM_INT);

        $stmt->execute();
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);

        return $stmt->fetch() ?: [];
    }

    protected function getColumns()
    {
        return implode(',', $this->columns);
    }

    public function getAll($returnType = \PDO::FETCH_OBJ)
    {
        $sql = "SELECT {$this->getColumns()} ".
                "FROM {$this->table} ";

        return $this->db->query($sql)->fetchAll($returnType);
    }
}
