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

    public function __construct($id, $name, $address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
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
     * @param array $params
     * @return Venue
     */
    public static function create(array $params = []) : Venue
    {
        return new self(
            $params['id'],
            $params['name'],
            $params['address']
        );
    }

}