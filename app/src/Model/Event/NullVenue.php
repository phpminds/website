<?php namespace PHPMinds\Model\Event;

class NullVenue implements VenueInterface
{

    public function getName()
    {
        return '';
    }

    public function getAddress()
    {
        return '';
    }

    public function getContinent()
    {
        return '';
    }
}