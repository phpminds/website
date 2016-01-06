<?php

namespace PHPMinds\Factory;

use PHPMinds\Model\Email;
use PHPMinds\Model\Event\Entity\Speaker;
use PHPMinds\Model\Event\Entity\Supporter;
use PHPMinds\Model\Event\Entity\Talk;
use PHPMinds\Model\Event\Entity\Venue;
use PHPMinds\Model\Event\EventModel;
use PHPMinds\Model\Event\NullSpeaker;
use PHPMinds\Model\Event\NullSupporter;
use PHPMinds\Model\Event\NullTalk;
use PHPMinds\Model\Event\NullVenue;
use PHPMinds\Model\Twitter;
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

        $event = new EventModel(
            $talk,
            $date,
            $venue,
            $supporter
        );

        $event->setName($title);
        $event->setDescription($description);

        return $event;
    }

    public static function getMergedFromArrays(array $meetupEvent = [], array $dbEvent = null)
    {
        if (!is_null($dbEvent)) {

            $speaker = new Speaker(
                $dbEvent['first_name'], $dbEvent['last_name'],
                new Email($dbEvent['email']),
                new Twitter($dbEvent['twitter']),
                $dbEvent['avatar']
            );

            $supporter = new Supporter(
                $dbEvent['supporter_name'], $dbEvent['supporter_url'],
                new Twitter($dbEvent['supporter_twitter']),
                new Email($dbEvent['supporter_email']),
                $dbEvent['supporter_logo']
            );


        } else {
            $speaker    = new NullSpeaker();
            $supporter  = new NullSupporter();
        }

        $talk = new Talk($meetupEvent['subject'], $meetupEvent['description'], $speaker);
        $venue = new Venue($meetupEvent['venue_name'], $meetupEvent['venue_address']);
        $venue->setId($meetupEvent['venue_id']);

        $date = \DateTime::createFromFormat('F jS Y g:ia', $meetupEvent['date'] . ' ' . $meetupEvent['time']);

        $event = new EventModel(
            $talk,
            $date,
            $venue,
            $supporter
        );

        $event->setName($meetupEvent['group']);
        $event->setMindsUrl($meetupEvent['minds_url']);
        $event->setMeetupID($meetupEvent['id']);
        $event->setMeetupURL($meetupEvent['event_url']);

        if (!is_null($dbEvent)) {
            $event->eventExists();
        }

        return $event;
    }
}
