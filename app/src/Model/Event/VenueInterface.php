<?php namespace PHPMinds\Model\Event;

interface VenueInterface
{
    public function getName();

    public function getAddress();

    public function getContinent();
}