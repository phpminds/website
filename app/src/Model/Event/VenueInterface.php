<?php namespace PHPMinds\Model\Event;

interface VenueInterface
{
    public function getId();

    public function getName();

    public function getAddress();

    public function getContinent();
}