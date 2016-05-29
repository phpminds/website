<?php namespace PHPMinds\Model\Event;


class NullSpeaker implements SpeakerInterface
{
    public function getId()
    {
        return -1;
    }

    public function getFirstName()
    {
        return '';
    }

    public function getLastName()
    {
        return '';
    }

    public function getEmail()
    {
        return '';
    }

    public function getTwitter()
    {
        return '';
    }

    public function exists()
    {
        return false;
    }
}