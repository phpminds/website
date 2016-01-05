<?php namespace PHPMinds\Model\Event;


interface TalkInterface
{
    public function getTitle();

    public function getDescription();

    public function getSpeaker();
}