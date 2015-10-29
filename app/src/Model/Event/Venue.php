<?php

namespace App\Model\Event;

class Venue
{
    private $name;

    private $address;

    public function __construct($name, $address)
    {
        $this->name = $name;
        $this->address = $address;
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

}