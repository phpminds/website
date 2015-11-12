<?php

namespace App\Model\Event\Entity;

class Venue
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $continent;

    public function __construct($name, $address, $continent = 'Europe')
    {
        $this->name = $name;
        $this->address = $address;
        $this->continent = $continent;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getContinent()
    {
        return $this->continent;
    }

    /**
     * @param array $params
     * @return Venue
     */
    public static function create(array $params = []) : Venue
    {
        $class = new self(
            $params['name'] ?? null,
            $params['address'] ?? null,
            $params['continent'] ?? 'Europe'
        );


        $class->setId($params['id']);

        return $class;
    }

}