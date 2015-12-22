<?php

namespace App\Factory;

use App\Model\Event\Entity\Talk;
use App\Model\Event\Event;
use Slim\Http\Request;

class EventFactory
{
    public static function getByRequest(Request $request, $speaker, $venue, $supporter, $title, $description)
    {
        $talk =  new Talk(
            strip_tags($request->getParam('talk_title'), '<p><a><br>'),
            strip_tags($request->getParam('talk_description'), '<p><img><a><br>'),
            $speaker
        );

        $event = new Event(
            $talk,
            \DateTime::createFromFormat(
                "d/m/Y H:i",
                $request->getParam('start_date') . ' '
                . ($request->getParam('start_time') < 10 ? '0' . $request->getParam('start_time') :  $request->getParam('start_time'))

            ),
            $venue,
            $supporter
        );

        $event->setName($title);
        $event->setDescription($description);

        return $event;
    }
}