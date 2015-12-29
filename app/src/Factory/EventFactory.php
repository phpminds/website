<?php

namespace PHPMinds\Factory;

use PHPMinds\Model\Event\Entity\Talk;
use PHPMinds\Model\Event\Event;
use Slim\Http\Request;

class EventFactory
{
    public static function getEvent($talkTitle, $talkDescription, $date, $speaker, $venue, $supporter, $title, $description)
    {
        $talk =  new Talk(
            strip_tags($talkTitle, '<p><a><br>'),
            strip_tags($talkDescription, '<p><img><a><br>'),
            $speaker
        );

        $event = new Event(
            $talk,
            $date,
            $venue,
            $supporter
        );

        $event->setName($title);
        $event->setDescription($description);

        return $event;
    }
}