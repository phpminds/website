<?php namespace PHPMinds\Model\Event;


class NullTalk implements TalkInterface
{

    public function getTitle()
    {
        return '';
    }

    public function getDescription()
    {
        return '';
    }

    public function getSpeaker()
    {
        return new NullSpeaker();
    }
}