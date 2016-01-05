<?php namespace PHPMinds\Model\Event;


interface SpeakerInterface
{
    public function getFirstName();

    public function getLastName();

    public function getEmail();

    public function getTwitter();
}